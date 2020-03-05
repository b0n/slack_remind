<?php
/**
 * slackでリマインダを設定します
 *
 * @see https://slack.com/intl/ja-jp/help/articles/208423427
 */
$from   = new DateTime('09:00:00');
$end    = new DateTime('18:00:00');
$open   = clone $from;
$close  = clone $open;
while ($open < $end) {
    $open->add(new DateInterval('PT1H'));
    $close = clone $open;
    $close->add(new DateInterval('PT10M'));
    slack::remind("open the window", $open->format('H:i:s'));
    //slack::remind("open the window", $open->format('H:i:s') . " every weekday");
    sleep(3);
    slack::remind("close the window", $close->format('H:i:s'));
    //slack::remind("close the window", $close->format('H:i:s') . " every weekday");
    sleep(3);
}

/**
 * Class slack
 */
class slack
{

    /**
     * slackへリマインダを飛ばします
     *
     * @param $text
     * @param $time
     *
     * @see https://api.slack.com/methods/reminders.add
     */
    public static function remind($text, $time)
    {
        $url     = "https://slack.com/api/reminders.add";
        $token   = "xoxp-999999";
        $user    = "U0XXXXXX";
        $data    = array(
            "token"  => $token,
            "text"   => $text,
            "time"   => $time,
            "user"   => $user,
            "pretty" => 1,
        );
        $options = array(
            'http' => array(
                'method' => 'POST',
            ),
            'ssl'  => array(
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ),
        );
        print_r($data);
        $query = http_build_query($data);
        $url   = $url . '?' . $query;
        $ret   = file_get_contents($url, false, stream_context_create($options));
        echo $ret;
    }
}
