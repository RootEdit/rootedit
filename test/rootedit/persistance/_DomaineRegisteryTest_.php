<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DomaineRegisteryTest
 *
 * @author romai
 */
class DomaineRegisteryTest {

    protected function setUp($dic) {
        $DIC = new \fr\webetplus\rootedit\ioc\Container();
        $DIC->set('dataSource', MySQLPDO, 'dataBase', 'root', '', 'localhost');

        $domaineRegistery = new \fr\webetplus\rootedit\persistance\DomaineRegistery($DIC);
        $domaineRegistery->set('voiture' , '\domaine\VoitureRepository' , $DIC )  ;                  
    }

    public function testUtilisation($param) {
        //get generique 
        /**
         * @var \fr\webetplus\rootedit\persistance\PersistanceRepositories gererique repository
         */
        $article = $domaineRegistery->articles->ofId(1);
        $domaineRegistery->articles->ofQuery('GET /Articles/1/monProfesseur/2?limite=10&q=math&physique');


        //get specifique 
        /**
         * @var \fr\maBoite\MonApli\VoitureRepositories specifique repository
         */
        $MaClasseRepository = $domaineRegistery->get('Voitre'); //???
        $MaClasseRepository->ofId(1);
        $MaClasseRepository->ofQuery('GET /Voiture/1/monProfesseur/2?limite=10&q=math&physique');
        //requette spécifique : 
        $MaClasseRepository->byColor();
    }

}
