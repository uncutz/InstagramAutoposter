<?php

namespace Aeon\Cli;

use Aeon\Controller\InstagramController;
use Aeon\Controller\PixabayController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishPost extends Command
{
    public function configure(): void
    {
        $this->setName('instagram:post');
        $this->addArgument('debug', InputArgument::OPTIONAL, 'debug');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws \JsonException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if($input->getArgument('debug')) {
            $output->writeln('<comment>Info</comment> Debug is ON');
        }
        $instagramController = new InstagramController();
        $pixabayContoller    = new PixabayController();

        $output->writeln('searching for image...');
        $imageData = $pixabayContoller->getImage('nature');
        $output->writeln('Found!');
        $output->writeln('Fetch Instagram user id...');
        $userId = $instagramController->getInstagramUserId();
        $output->writeln('Fetched!');
        if($input->getArgument('debug')) {
            $output->writeln('Image data: ');
            $output->writeln(print_r($imageData));
            $output->writeln('Instagram user id: ');
            $output->writeln(print_r($userId));
            return 1;
        }
        $output->writeln('Publish post...');
        $response = $instagramController->postImageOnInstagram($userId, $imageData['imageUrl'], $imageData['hashtags']);
        $output->writeln(print_r($response));
        return 1;
    }
}