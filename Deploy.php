<?php

defined('ROOT') || die('Not allowed');

/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 07.11.16
 * Time: 19:12
 */
class Deploy
{
    /**
     * A callback function to call after the deploy has finished.
     *
     * @var callback
     */
    public $post_deploy;

    /**
     * The name of the file that will be used for logging deployments. Set to
     * FALSE to disable logging.
     *
     * @var string
     */
    private $_log = false;

    /**
     * The timestamp format used for logging.
     *
     * @link    http://www.php.net/manual/en/function.date.php
     * @var     string
     */
    private $_date_format = 'Y-m-d H:i:sP';

    /**
     * The name of the branch to pull from.
     *
     * @var string
     */
    private $_branch = 'master';

    /**
     * The name of the remote to pull from.
     *
     * @var string
     */
    private $_remote = 'origin';

    /**
     * The directory where your website and git repository are located, can be
     * a relative or absolute path
     *
     * @var stringw
     */
    private $_directory;

    /**
     * Sets up defaults.
     *
     * @param  string $directory Directory where your website is located
     * @param  array $options Information about the deployment
     */
    public function __construct($directory, $options = [])
    {
        // Determine the directory path
        $this->_directory = realpath($directory) . DIRECTORY_SEPARATOR;

        $available_options = ['log', 'date_format', 'branch', 'remote'];

        foreach ($options as $option => $value) {
            if (in_array($option, $available_options)) {
                $this->{'_' . $option} = $value;
            }
        }

        $this->log('Attempting deployment...');
    }

    /**
     * Writes a message to the log file.
     *
     * @param  string $message The message to write
     * @param  string $type The type of log message (e.g. INFO, DEBUG, ERROR, etc.)
     */
    public function log($message, $type = 'INFO')
    {
        echo $message . PHP_EOL;

        if (!$this->_log) {
            return;
        }

        // Getting current working directory
        $currentDirectory = getcwd() ?: ROOT;

        // Returning to ROOT directory
        chdir(ROOT);

        // Set the name of the log file
        $filename = $this->_log;

        // Write the message into the log file
        // Format: time --- type: message
        file_put_contents($filename, date($this->_date_format) . ' --- ' . $type . ': ' . $message . PHP_EOL, FILE_APPEND);

        // Returning to current working directory
        chdir($currentDirectory);
    }

    /**
     * Executes the necessary commands to deploy the website.
     */
    public function execute()
    {
        try {
            // Make sure we're in the right directory
            chdir($this->_directory);
            $this->log('Changing working directory... ');

            if (!file_exists('.git') || !is_dir('.git')) {
                throw new Exception(".git directory does not exists in #{$this->_directory}");
            }

            // Discard any changes to tracked files since our last deploy
            exec('git reset --hard HEAD', $output);
            $this->log('Reseting repository... ' . implode(' ', $output));

            // Update the local repository
            exec('git pull ' . $this->_remote . ' ' . $this->_branch, $output);
            $this->log('Pulling in changes... ' . implode(' ', $output));

            // Secure the .git directory
            exec('chmod -R og-rx .git');
            $this->log('Securing .git directory... ');

            $isCallable = is_callable($this->post_deploy);

            if (is_callable($this->post_deploy)) {
                call_user_func($this->post_deploy, [&$this]);
            }

            $this->log('Deployment successful.');
        } catch (Exception $e) {
            $this->log($e->getMessage(), 'ERROR');
        }
    }

    /**
     * @param callable $func
     */
    public function setPostDeploy(callable $func = null)
    {
        $this->post_deploy = $func;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function runPostDeploy()
    {
        if (is_callable($this->post_deploy)) {
            $postDeployResult = call_user_func($this->post_deploy, [$this]);

            if (!$postDeployResult) {
                throw new Exception("Error while trying to run post deploy callable");
            }

            return true;
        }

        return true;
    }
}