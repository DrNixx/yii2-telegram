<?php
namespace onix\telegram\commands\system;

use onix\telegram\commands\SystemCommand;
use onix\telegram\entities\ServerResponse;

/**
 * Start command
 */
class StartCommand extends SystemCommand
{
    /**
     * @inheritdoc
     */
    protected $name = 'start';

    /**
     * @inheritdoc
     */
    protected $description = 'Start command';

    /**
     * @inheritdoc
     */
    protected $usage = '/start';

    /**
     * @inheritdoc
     */
    protected $version = '1.2.0';

    /**
     * @inheritdoc
     */
    protected $show_in_help = false;

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
