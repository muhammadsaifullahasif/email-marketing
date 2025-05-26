<?php


require_once(__DIR__ . '/vendor/autoload.php');

// Write folder content to log every five minutes.
$job1 = new \Cron\Job\ShellJob();
// $job1->setCommand('ls -la /cron-job.php');
$job1->setCommand('/usr/local/bin/php http://localhost/email-marketing/api/cron-job.php');
$job1->setSchedule(new \Cron\Schedule\CrontabSchedule('* * * * *'));

// Remove folder contents every hour.
/*
$job2 = new \Cron\Job\ShellJob();
$job2->setCommand('rm -rf /path/to/folder/*');
$job2->setSchedule(new \Cron\Schedule\CrontabSchedule('0 0 * * *'));
*/

$resolver = new \Cron\Resolver\ArrayResolver();
$resolver->addJob($job1);

$cron = new \Cron\Cron();
$cron->setExecutor(new \Cron\Executor\Executor());
$cron->setResolver($resolver);

if($cron->run()) {
    echo "Successfully";
} else {
    echo "Error";
}