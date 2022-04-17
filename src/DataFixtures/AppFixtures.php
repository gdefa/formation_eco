<?php

namespace App\DataFixtures;


use App\Entity\formation;
use App\Entity\lesson;
use App\Entity\Progress;
use App\Entity\quizz;
use App\Entity\section;
use App\Entity\user;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // admin
        $admin = new User();
        $admin->setEmail('defa7@live.fr');
        $admin->setPseudo('admin');
        $passwordAdmin = $this->hasher->hashPassword($admin, 'administrateur');
        $admin->setPassword($passwordAdmin);
        $admin->setIsAccepted(true);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Instructeur non validé
        $instructeurN = new User();
        $instructeurN->setEmail('instructeurnotvalid@email.com');
        $instructeurN->setRoles(['ROLE_INSTRUCTEUR']);
        $passwordInstructeurN = $this->hasher->hashPassword($instructeurN, 'instructeur');
        $instructeurN->setPassword($passwordInstructeurN);
        $instructeurN->setFullname($faker->firstName());
        $instructeurN->setName($faker->name());
        $instructeurN->setProfilpicture('\Images\photo_profil.jpg');
        $instructeurN->setDescription($faker->words(3, true));
        $instructeurN->setIsAccepted(false);

        $manager->persist($instructeurN);

        // Instructeur validé
        $instructeur = new User();
        $instructeur->setEmail('instructeur@email.com');
        $instructeur->setRoles(['ROLE_INSTRUCTEUR']);
        $passwordInstructeur = $this->hasher->hashPassword($instructeur, 'instructeur');
        $instructeur->setPassword($passwordInstructeur);
        $instructeur->setFullname($faker->firstName());
        $instructeur->setName($faker->name());
        $instructeur->setProfilpicture('\Images\photo_profil2.jpg');
        $instructeur->setDescription($faker->words(3, true));
        $instructeur->setIsAccepted(true);

        $manager->persist($instructeur);

        // Apprenant

        $apprenant = new User();
        $apprenant->setEmail('apprenant@gmail.com');
        $apprenant->setRoles(['ROLE_APPRENANT']);
        $passwordApprenant = $this->hasher->hashPassword($apprenant, 'apprenant');
        $apprenant->setPassword($passwordApprenant);
        $apprenant->setPseudo($faker->name());
        $apprenant->setIsAccepted(true);

        $manager->persist($apprenant);

        for ($f = 0; $f <= 10; $f++) {
            $formation = new Formation();
            $formation->setUser($instructeur);
            $formation->setPicture('\formation.jpg');
            $formation->setDescription($faker->words(30, true));
            $formation->setTitle($faker->words(3, true));

            $manager->persist($formation);

            for ($s = 0; $s <= 5; $s++) {
                $section = new Section();
                $section->setFormation($formation);
                $section->setTitle($faker->words(3, true));

                $manager->persist($section);

                for ($l = 0; $l <= 10; $l++) {
                    $lesson = new Lesson();
                    $lesson->setTitle($faker->words(4, true));
                    $lesson->setSection($section);
                    $lesson->setContent($faker->text(6000));
                    $lesson->setPicture('\laptopforet.png');
                    $lesson->setVideo('https://www.youtube.com/watch?v=EZNM0AiAEeA');

                    $manager->persist($lesson);



                    $quizz = new Quizz();
                    $quizz->setSection($section);
                    $quizz->setUser(null);
                    $quizz->setQuestion($faker->words(10, true));
                    $quizz->setAnswer(true);
                    $quizz->setQuestion2($faker->words(10, true));
                    $quizz->setAnswer2(false);
                    $quizz->setQuestion3($faker->words(20, true));
                    $quizz->setAnswer3(false);


                    $manager->persist($quizz);

                }
            }



        }


        /////
        $manager->flush();
    }
}