<?php

class DbConnectionException extends Exception
{
}

/**
 * DB Connection
 * In fact just a PDO Object with some preconfigured Attributes
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 */
class DBConnection extends PDO
{
    public function __construct($cfgFile)
    {
        if(!file_exists($cfgFile)) throw new DbConnectionException("Config {$cfgFile} does not exist.");
        try {
            $config = require($cfgFile);
            if(empty($config) || !is_array($config)) throw new DbConnectionException("Config {$config} must return array of configuration settings.");
            parent::__construct($config["dsn"], $config["user"], $config["password"]);
            // always throw exceptions
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // force column names to be lower case
            $this->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            // for performance reasons
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            // set default fetchmode
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            switch($this->getAttribute(PDO::ATTR_DRIVER_NAME)) {
                case "mysql":
                    $this->nameOpening = $this->nameClosing = "`";
                    $this->exec("SET CHARACTER SET utf8");
                    break;
                case "mssql":
                    $this->nameOpening = "[";
                    $this->nameClosing = "]";
                    // add utf-8 support
                    break;
                default:
                    $this->nameOpening = $this->nameClosing = "\"";
            }
        } catch(PDOException $e) {
            throw new DbConnectionException($e->getMessage());
        }
    }
}

?>