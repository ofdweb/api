<?php
/**
 * Created by PhpStorm.
 * User: itily
 * Date: 09.10.2020
 * Time: 10:54
 */

namespace app\jobs;


use yii\base\Component;
use yii\queue\Job;

/**
 * Send email to admin
 *
 * Class EmailSendJob
 * @package app\jobs
 */
class EmailSendJob extends Component implements Job
{
    /**
     * @var string
     */
    public $body;
    /**
     * @var string
     */
    public $from;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $subject;
    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        $email = Yii::$app->params['adminEmail'];

        try {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->from => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), 'error');
        }
    }
}