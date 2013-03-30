Crontab
=======

Simple, independant and local PHP Crontab package

The package helps you manage your local cron jobs using PHP. You can list, append and remove your jobs and set your log file.

## Requirements:
Should work on monst Linux flavoured systems. (I have yet to test on windows)

## Usage Example
Use [composer](http://getcomposer.org) to install it or simply include the file somewhere: require("crontab/src/Crontab/Crontab.php");

Append two new jobs and set them to run every minute:

```php
    $cron = new \Crontab\Crontab();
    $cron->setMinute("*");
    $cron->setHour("*");
    $cron->setDayOfMonth("*");
    $cron->setMonth("*");
    $cron->setDayOfWeek("*");
    $cron->append(array(
                "date",
                "ls -all"
            )
        );
    $commandsList = $cron->execute();
```

## Methods

#### append($command)

Appends a new job to the current cronjob list

Parameters: $command : String or array of commands.

```php
  $cron = new \Crontab\Crontab();
  $cron->setMinute("*");
  $cron->setHour("*");
  $cron->setDayOfMonth("*");
  $cron->setMonth("*");
  $cron->setDayOfWeek("*");
  $cron->append("date");
  $cron->execute();
```

#### remove($command)

Removes a job from the current cronjob list. You must recreate the exact job to remove it.

Parameters: $command : String or array of commands

```php
  $cron = new \Crontab\Crontab();
  $cron->setMinute("*");
  $cron->setHour("*");
  $cron->setDayOfMonth("*");
  $cron->setMonth("*");
  $cron->setDayOfWeek("*");
  $cron->remove("date");
  $cron->execute();
```

#### getJobs()
Return a current list of jobs with there hashed keys

```php
  $cron = new \Crontab\Crontab();
  $cron->setMinute("*");
  $cron->setHour("*");
  $cron->setDayOfMonth("*");
  $cron->setMonth("*");
  $cron->setDayOfWeek("*");
  $cron->remove("date");
  $cron->execute();
```

#### removeByKey($key)

Removes a job from the current cronjob list by a hash key. Found by running execute() or getJobs()

Parameters: $key : String or array of keys

```php
  $cron = new \Crontab\Crontab();
  $cron->removeByKey("1231231231231231231");
  $cron->execute();
```

#### execute()
Applies and writes the new cronjob list.
```php
    $cron->setMinute("*");
    $cron->setHour("*");
    $cron->setDayOfMonth("*");
    $cron->setMonth("*");
    $cron->setDayOfWeek("*");
    $cron->append("date");
    $cron->execute();
```

#### clear()
Simply removes all running jobs by executing crontab -r
```
    $cron = new \Crontab\Crontab();
    $cron->clear();
```

## Settings
Settings can also be applied to the constuct method like so:
```php
    $conf = array(
		'minute' => '*',
		'hour' => '*'
		'dayOfMonth' => '*',
		'month' => '*',
		'dayOfWeek' => '*',
		'logFile' => 'log.txt',
		'tmpFile' => 'jobs.txt'
	);
    
    $cron = new \Crontab\Crontab($conf);
```

### Setting functions (Defaults are all "*" here)
```
setMinute($m) : Sets the minute.
setHour($h) : Sets the hour.
setDayOfMonth($dom) : Sets the date of the month.
setMonth($m) : Sets the month.
setDayOfWeek($dow) : Sets the day of the week.
```

### Log and tempory file settings
```
setLogFile($v) : Sets the log file and will attempt to create it. The default is /dev/null ie: nothing logged
setTmpFile($v) : Sets the tempory file used by crontab to read from, this file is automatically removed. The default is "jobs.txt"
```

## Helpers
Execute this job in 5 minutes from now.
```php
    $cron = new \Crontab\Crontab();
    $cron->minuteFromNow(5);
    $cron->execute();
```

## Help understanding cronjobs
A Google search should provied plenty of links but check out: [Kevin van Zonneveld's blog](http://kvz.io/blog/2007/07/29/schedule-tasks-on-linux-using-crontab/) if you need help.
    
## Disclaimer
Use this library at your own risk.
