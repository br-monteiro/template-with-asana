<?php
namespace App;

class Config
{

    const DS = DIRECTORY_SEPARATOR;
    const FREQUENCY_UPDATE = 259200; // 3 days in seconds
    const DAYS = 1;
    const FILE_CONFIG = self::DS . '..' . self::DS . '..' . self::DS . 'config.json';
    const PR = 'https://github.com/search?utf8=✓&q=org%3Achaordic+' . self::TAG . '&type=issues';
    const MR = 'http://git.neemu.com/search?utf8=%E2%9C%93&search=' . self::TAG;
    const TAG = '%TAG%';
    const LINK = '%LINK%';
    const DESCRIPTION = '%DESCRIPTION%';
    const SOLUTION = '%SOLUTION%';
    const OBSERVATION = '%OBSERVATION%';
    const HASHTAG = '%HASHTAGS%';
    const FOLLOWER = '%FOLLOWER%';
}
