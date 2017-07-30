<?php

require_once('./vendor/autoload.php');

use Symfony\Component\Process\Process;

$output = '';
$fs = new \Symfony\Component\Filesystem\Filesystem();
if ($fs->exists('output.txt')) {
    $fs->remove('output.txt');
}
$execute = function (string $command, string $path, bool $catch = false) use (&$output) {
    $process = new Process($command, $path);

    try {
        $result = $process->mustRun()->getOutput();
    } catch (\Symfony\Component\Process\Exception\ProcessFailedException $ex) {
        if (!$catch) {
            throw $ex;
        }
        $result = $ex->getProcess()->getOutput();
    }

    $output = $output . "Executing:\t$command\n\n$result\n";

    return $output;
};
try {

    /**
     * @var string $repoLocal
     * @var string $repoRemote
     */
    foreach (['repoLocal', 'repoRemote'] as $repoName) {
        $repoPath = __DIR__ . '/tests/output/' . $repoName;
        // We need empty folder (in case our previous test was broken)
        if ($fs->exists($repoPath)) {
            $fs->remove($repoPath);
        }
        $fs->mkdir($repoPath);

        $execute('git init', $repoPath);

        $$repoName = $repoPath;
    }

    file_put_contents($repoRemote . '/file_to_be_edited', "Line 1\n");
    file_put_contents($repoRemote . '/file_to_change_permissions', '');
    file_put_contents($repoRemote . '/file_to_be_deleted', '');

    $execute('git add .', $repoRemote);
    $execute('git commit -m "Initial commit"', $repoRemote);

    $execute("git remote add origin $repoRemote/.git", $repoLocal);
    $execute('git pull origin master', $repoLocal);

    file_put_contents(
        $repoRemote . '/file_to_be_edited',
        "Line 1 changed\nLine 2\n"
    );

    $fs->remove($repoRemote . '/file_to_be_deleted');
    $fs->chmod($repoRemote . '/file_to_change_permissions', 777);

    $execute('git commit -a -m "Second commit"', $repoRemote);
    $execute("git pull origin master --ff-only", $repoLocal);

    $execute("git pull origin master", $repoLocal);
    $execute("git reset HEAD~1 --hard", $repoLocal);
    $execute("git diff origin/master", $repoLocal);

    file_put_contents($repoLocal . '/file_to_be_edited', "Line 2 alternative changed\n");

    $execute("git pull origin master --ff-only", $repoLocal, true);

    $execute("git diff -R origin/master", $repoLocal);

} catch (\Throwable $ex) {
    if ($ex instanceof \Symfony\Component\Process\Exception\ProcessFailedException) {
        echo $ex->getMessage();
        exit;
    } else {
        xdebug_break();
    }
    $output .= 'Exception ' . get_class($ex);
}

$fs->appendToFile('output.txt', $output);
echo $output;