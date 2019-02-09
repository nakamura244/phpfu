<?php

namespace Nakamura244\Phpfu;

class Analyser
{

    public function execute(string $filename)
    {
        return $this->extract(
            $this->parse(
                $this->getOptimizedByteCode($filename)
            )
        );
    }

    private function getOptimizedByteCode(string $filename): array
    {
        return $this->exe(
            \PHP_BINARY . ' ' . '-d opcache.opt_debug_level=0x40000  -d opcache.enable_cli=1' . ' ' . $filename . ' 2>&1'
        );
    }

    /**
     * @param string $command
     * @return array
     */
    private function exe(string $command): array
    {
        exec($command, $output, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException(implode("\r\n", $output));
        }
        return $output;
    }

    private function parse(array $lines): array
    {
        $opcodes = [];
        $isMethod = false;
        $methodName = "";
        foreach ($lines as $line) {
            if ($line === "") {
                continue;
            }
            if (strpos($line, ': ;') !== false) {
                $methodName = explode(';', $line)[0];
                $isMethod = true;
            }
            if ($isMethod && $methodName != $line) {
                if (strpos($line, '; /') !== false) {
                    $opcodes[$methodName]['line'] = trim(explode(';', $line)[1]);
                } else {
                    $opcodes[$methodName][] = trim($line);
                }

            }
            if ($line === "") {
                $isMethod = false;
            }
        }
        return $opcodes;
    }

    private function extract(array $lines): array
    {
        $opcodes = [];
        foreach ($lines as $k => $vals) {
            foreach ($vals as $v) {
                //$opcodes[$k][] = $v;
                if (strpos($v, 'unreachable') !== false) {
                    $opcodes[$k . $vals['line']][] = $v;
                    //$opcodes[$k][] = $vals['line'];
                }
            }
        }
        return $opcodes;
    }
}