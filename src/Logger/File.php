<?php

declare(strict_types=1);

namespace LDG\Logger;

class File implements LoggerInterface
{
    private const MODE = 'w';
    private const DIR_PERMISSION = 0775;
    private const FILE_PERMISSION = 0664;

    private string $dir;

    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->createDirIfNotExists($this->dir);
    }

    public static function build(string $dir): self
    {
        return new self($dir);
    }

    public function clear(string $name): void
    {
        if (is_dir($this->dir)) {
            if ($dh = opendir($this->dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (strpos($file, $name) !== false) {
                        unlink($this->dir . DIRECTORY_SEPARATOR . $file);
                    }
                }
                closedir($dh);
            }
        }
    }

    public function write(string $name, string $content): void
    {
        $file = $this->file($name);
        $handle = @fopen($file, self::MODE);
        if ($handle) {
            if (fwrite($handle, $content)) {
                self::assignPermissions($file, self::FILE_PERMISSION);
            }

            fclose($handle);
        }
    }

    private function file(string $name): string
    {
        return sprintf('%s/%s_%s.log', $this->dir, $name, date('Ymd'));
    }

    private function createDirIfNotExists(string $dir): void
    {
        $beforeDir = dirname($dir);
        if (!is_dir($beforeDir)) {
            self::createDirIfNotExists($beforeDir);
        }

        if (is_dir($beforeDir)) {
            if (!is_dir($dir)) {
                @mkdir($dir, self::FILE_PERMISSION);
                if (is_dir($dir)) {
                    self::assignPermissions($dir);
                }
            } else {
                self::assignPermissions($dir);
            }
        }
    }

    private static function assignPermissions(string $dirFile, $permission = self::DIR_PERMISSION): void
    {
        if (is_dir($dirFile) || file_exists($dirFile)) {
            // get permission of file in octal format: 100777 => 0777
            $filePermission = decoct(fileperms($dirFile));
            $permissionForValidate = substr($filePermission, -3);
            if ($permissionForValidate != decoct($permission)) {
                chmod($dirFile, $permission);
            }
        }
    }
}
