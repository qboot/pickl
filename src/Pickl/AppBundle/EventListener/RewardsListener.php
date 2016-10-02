<?php

namespace Pickl\AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Pickl\AppBundle\Controller\TokenAuthenticatedController;
use Pickl\AppBundle\Entity\UserReward;

class RewardsListener
{
    protected $router;
    protected $requestStack;
    protected $container;

    /**
    * @param $router
    * @param $container
    */
    public function __construct($router, $container, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
    * @param FilterControllerEvent $event
    */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {
        //code à executer
            $this->assignRewards();
        }
    }

    // FONCTION PERSO TESTER LES RÉCOMPENSES
    public function assignRewards() {

        $securityContext = $this->container->get('security.authorization_checker');

        // si l'utilisateur est connecté on return
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return;

        // get current user and entity manager
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->container->get('doctrine')->getManager();
        $request = $this->requestStack->getCurrentRequest();

        $rewardRepository = $em->getRepository('PicklAppBundle:Reward');

        $rewardsUser = $user->getRewards();
            $contributions = $user->getContributions();
            $nbComments = count($user->getComments());
            $nbFavorites = count($user->getFavorites());
            $projects = $user->getProjects();
            $nbProjects = 0;
            foreach($projects as $project) {
                if($project->getPublished())
                    $nbProjects++;
            }
            $rewards = $em->getRepository('PicklAppBundle:Reward')->findAll();

            $tabRewards = array();
            foreach($rewardsUser as $rewardUser)
            {
                foreach($rewards as $reward)
                {
                    if($rewardUser->getReward() == $reward)
                    {
                        if(!in_array($reward,$tabRewards))
                            $tabRewards[] = $reward;
                    }
                }
            }

            $somme = 0;
            foreach ($contributions as $contribution) {
                $somme += $contribution->getAmount();
            }

            $newsletterMails = $em->getRepository('PicklAppBundle:Newsletter')->findAll();

            $matchNewsletter = false;
            foreach ($newsletterMails as $newsletterMail) {
                if ($newsletterMail->getMail() == $user->getEmail()) {
                    $matchNewsletter = true;
                    break;
                }
            }

            // RECOMPENSE 1 : 1 commentaire
            if(isset($rewards[0])) {
                if(!in_array($rewards[0],$tabRewards)) {
                    // on fait la vérif

                    if ($nbComments >= 1) {
                        // on récupère la récompense
                        $reward = $rewards[0];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . ' !');
                        }
                    }
                }
            }

            // RECOMPENSE 2 : 25 commentaires
            if(isset($rewards[1])) {
                if(!in_array($rewards[1],$tabRewards)) {
                    // on fait la vérif

                    if ($nbComments >= 25) {
                        // on récupère la récompense
                        $reward = $rewards[1];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 3 : 50 commentaires
            if(isset($rewards[2])) {
                if(!in_array($rewards[2],$tabRewards)) {
                    // on fait la vérif

                    if ($nbComments >= 50) {
                        // on récupère la récompense
                        $reward = $rewards[2];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 4 : 100 commentaires
            if(isset($rewards[3])) {
                if(!in_array($rewards[3],$tabRewards)) {
                    // on fait la vérif

                    if ($nbComments >= 100) {
                        // on récupère la récompense
                        $reward = $rewards[3];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 5 : 1000 commentaires
            if(isset($rewards[4])) {
                if(!in_array($rewards[4],$tabRewards)) {
                    // on fait la vérif

                    if ($nbComments >= 1000) {
                        // on récupère la récompense
                        $reward = $rewards[4];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 6 : 5000 commentaires
            if(isset($rewards[5])) {
                if(!in_array($rewards[5],$tabRewards)) {
                    // on fait la vérif

                    if ($nbComments >= 5000) {
                        // on récupère la récompense
                        $reward = $rewards[5];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 7 : 1 projet
            if(isset($rewards[6])) {
                if(!in_array($rewards[6],$tabRewards)) {
                    // on fait la vérif

                    if ($nbProjects >= 1) {
                        // on récupère la récompense
                        $reward = $rewards[6];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 8 : 3 projets
            if(isset($rewards[7])) {
                if(!in_array($rewards[7],$tabRewards)) {
                    // on fait la vérif

                    if ($nbProjects >= 3) {
                        // on récupère la récompense
                        $reward = $rewards[7];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 9 : 10 projets
            if(isset($rewards[8])) {
                if(!in_array($rewards[8],$tabRewards)) {
                    // on fait la vérif

                    if ($nbProjects >= 10) {
                        // on récupère la récompense
                        $reward = $rewards[8];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 10 : 1 like
            if(isset($rewards[9])) {
                if(!in_array($rewards[9],$tabRewards)) {
                    // on fait la vérif

                    if ($nbFavorites >= 1) {
                        // on récupère la récompense
                        $reward = $rewards[9];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 11 : 5 like
            if(isset($rewards[10])) {
                if(!in_array($rewards[10],$tabRewards)) {
                    // on fait la vérif

                    if ($nbFavorites >= 5) {
                        // on récupère la récompense
                        $reward = $rewards[10];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 12 : 25 like
            if(isset($rewards[11])) {
                if(!in_array($rewards[11],$tabRewards)) {
                    // on fait la vérif

                    if ($nbFavorites >= 25) {
                        // on récupère la récompense
                        $reward = $rewards[11];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 13 : 50 like
            if(isset($rewards[12])) {
                if(!in_array($rewards[12],$tabRewards)) {
                    // on fait la vérif

                    if ($nbFavorites >= 50) {
                        // on récupère la récompense
                        $reward = $rewards[12];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 14 : 100 like
            if(isset($rewards[13])) {
                if(!in_array($rewards[13],$tabRewards)) {
                    // on fait la vérif

                    if ($nbFavorites >= 100) {
                        // on récupère la récompense
                        $reward = $rewards[13];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 15 : 1$
            if(isset($rewards[14])) {
                if(!in_array($rewards[14],$tabRewards)) {
                    // on fait la vérif
                    if ($somme >= 1) {
                        // on récupère la récompense

                        $reward = $rewards[14];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 16 : 25$
            if(isset($rewards[15])) {
                if(!in_array($rewards[15],$tabRewards)) {
                    // on fait la vérif

                    if ($somme >= 25) {
                        // on récupère la récompense

                        $reward = $rewards[15];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 17 : 50$
            if(isset($rewards[16])) {
                if(!in_array($rewards[16],$tabRewards)) {
                    // on fait la vérif

                    if ($somme >= 50) {
                        // on récupère la récompense
                        $reward = $rewards[16];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 18 : 100$
            if(isset($rewards[17])) {
                if(!in_array($rewards[17],$tabRewards)) {
                    // on fait la vérif

                    if ($somme >= 100) {
                        // on récupère la récompense
                        $reward = $rewards[17];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 19 : 1000$
            if(isset($rewards[18])) {
                if(!in_array($rewards[18],$tabRewards)) {
                    // on fait la vérif

                    if ($somme >= 1000) {
                        // on récupère la récompense
                        $reward = $rewards[18];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 20 : 2500$
            if(isset($rewards[19])) {
                if(!in_array($rewards[19],$tabRewards)) {
                    // on fait la vérif

                    if ($somme >= 2500) {
                        // on récupère la récompense
                        $reward = $rewards[19];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 21 : 7jours
            if(isset($rewards[20])) {
                if(!in_array($rewards[20],$tabRewards)) {
                    // on fait la vérif
                    $registrationDate = $user->getRegistrationDate();
                    $currentDate = new \Datetime();
                    $interval = $currentDate->diff($registrationDate);

                    $days = (int)$interval->format("%a");

                    if ($days >= 7) {
                        // on récupère la récompense
                        $reward = $rewards[20];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 22 : 30jours
            if(isset($rewards[21])) {
                if(!in_array($rewards[21],$tabRewards)) {
                    // on fait la vérif
                    $registrationDate = $user->getRegistrationDate();
                    $currentDate = new \Datetime();
                    $interval = $currentDate->diff($registrationDate);

                    $days = (int)$interval->format("%a");

                    if ($days >= 30) {
                        // on récupère la récompense
                        $reward = $rewards[21];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 23 : 90jours
            if(isset($rewards[22])) {
                if(!in_array($rewards[22],$tabRewards)) {
                    // on fait la vérif
                    $registrationDate = $user->getRegistrationDate();
                    $currentDate = new \Datetime();
                    $interval = $currentDate->diff($registrationDate);

                    $days = (int)$interval->format("%a");

                    if ($days >= 90) {
                        // on récupère la récompense
                        $reward = $rewards[22];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 24 : 360jours
            if(isset($rewards[23])) {
                if(!in_array($rewards[23],$tabRewards)) {
                    // on fait la vérif
                    $registrationDate = $user->getRegistrationDate();
                    $currentDate = new \Datetime();
                    $interval = $currentDate->diff($registrationDate);

                    $days = (int)$interval->format("%a");

                    if ($days >= 360) {
                        // on récupère la récompense
                        $reward = $rewards[23];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 25 : 1080jours
            if(isset($rewards[24])) {
                if(!in_array($rewards[24],$tabRewards)) {
                    // on fait la vérif
                    $registrationDate = $user->getRegistrationDate();
                    $currentDate = new \Datetime();
                    $interval = $currentDate->diff($registrationDate);

                    $days = (int)$interval->format("%a");

                    if ($days >= 1080) {
                        // on récupère la récompense
                        $reward = $rewards[24];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 26 : newsletter
            if(isset($rewards[25])) {
                if(!in_array($rewards[25],$tabRewards)) {

                    if ($matchNewsletter) {
                        // on récupère la récompense
                        $reward = $rewards[25];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // RECOMPENSE 27 : creation compte
            if(isset($rewards[26])) {
                if(!in_array($rewards[26],$tabRewards)) {
                    if ($user->isEnabled()) {
                        // on récupère la récompense
                        $reward = $rewards[26];

                        if ($reward !== null) {
                            $userReward = new UserReward();
                            $userReward->setReward($reward);
                            $userReward->setUser($user);
                            $user->addReward($userReward);
                            $request->getSession()->getFlashBag()->add('info', 'New reward : ' . $reward->getName() . '!');
                        }
                    }
                }
            }

            // à la fin des tests on persiste et on flushe
            $em->persist($user);
            $em->flush();
    }
}