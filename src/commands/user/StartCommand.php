<?php
namespace onix\telegram\commands\user;

use onix\telegram\commands\UserCommand;
use onix\telegram\entities\ServerResponse;

/**
 * Start command
 */
class StartCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$chat_id = $message->getChat()->getId();
        //$user_id = $message->getFrom()->getId();

        return $this->request->emptyResponse();
    }
}
