Crontab
=======

Simple, independant and local PHP Crontab package

The package helps you manage your local cron jobs using PHP. You can list, append and remove your jobs and set your log file.

## Requirements:

Should work on monst Linux flavoured systems. (Not tried it on windows)

## Usage Example

Use [composer](http://getcomposer.org) to install it or simply include the file somewhere: require("crontab/src/Crontab/Crontab.php");

### Append two new jobs and set them to run every minute

```
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

```
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

Removes a job from the current cronjob list by recreated the previous append command.

Parameters: $command : String or array of commands

```
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

```
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

Removes a job from the current cronjob list by the hash key generated from the previous command.

Parameters: $key : String or array of keys

```
  $cron = new \Crontab\Crontab();
  $cron->removeByKey("1231231231231231231");
  $cron->execute();
```

#### execute()

Applies and writes the new cronjob list.

```
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

### Settings

setMinute($v) : Sets the minute.
setHour($v) : Sets the hour.
setDayOfMonth($v) : Sets the date of the month.
setMonth($v) : Sets the month.
setDayOfWeek($v) : Sets the day of the week.

(Defaults are all "*" here)

setLogFile($v) : Sets the log file and will attempt to create it. The default is /dev/null
setTmpFile($v) : Sets the tempory file used by crontab to read from, this file is automatically removed. The default is "jobs.txt"


## Disclaimer:

Use this library at your own risk.