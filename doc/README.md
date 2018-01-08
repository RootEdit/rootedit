# RootEdit
A micro framework for PHP.
* Design for developer
* focus on final user's needs

## installation

* enable autoloading for source file path : '/src/rootedit/' ( PSR-07 comptatible).
* you'r ready to go.

## usage 

### instantiate and run the application in the bootstrap :
```php
        try{
            $frontControler = new \fr\webetplus\rootedit\core\RootEdit();
            echo $frontControler->start()->getBody()->getContents();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
```

### Linking domaine's services as ressources consumed by application's services 
<!-- (Domaine driven design by Inversion of Controll by Dependenci Injection Container) -->;
```php
	$frontControler->container->set('dataSource', '\fr\webetplus\rootedit\persistance\MySQLPDO', 'domus', 'root', '', 'localhost');
        $frontControler->container->set('ecritureRepository', '\persistance\EcritureRepository', 'dataSource');
        $frontControler->container->set('AccountRepository', '\persistance\AccountRepository', 'dataSource');
        $frontControler->container->set('ajouterEcriture', '\domus\AddEntry');
```

### http routing from url to signal name
```php
	$frontControler->addRoute('ecriture/ajout/', 'ajoutEcriture');
        $frontControler->addRoute('ecriture/', 'main&ecriture');
        $frontControler->addRoute('livre/', 'main&livre');
        $frontControler->addRoute('prevision/', 'main&prevision');
        $frontControler->addRoute('/', 'main&index');
```

### routing signal to the output of template file
```php
        $frontControler->addTemplate('main', '#', '/view/index.phtml');
        $frontControler->addTemplate('livre', 'main', '/view/livre.phtml');
        $frontControler->addTemplate('ecriture', 'main', '/view/ecriture.phtml');
```

### routing signal to an http controller 
<!-- wich'll call use case aplication controller wich'll consume application services as ressources provided by the container -->;
```php
        $frontControler->addAction('main', function($in, $out, $front) {
            $out->header = "<h1>Header</h1>";
            $out->footer = "<h1>FOOTER</h1>";
        });
        $frontControler->addAction('index', function($in, $out, $front) {
            //$frontControler->container->listerCompte->
            $out->main = "<h1>Contenue :p</h1>";
        });
        $frontControler->addAction('livre', function($in, $out, $front) {
            // $frontControler->container->listerCompte->
        });
        $frontControler->addAction('ecriture', function($in, $out, $front) {

        });
        $frontControler->addAction('ajoutEcriture', function($in, $out, $front ) {
            $front->container->ajouterEcriture->add($in->number('idCompteDebit'), $in->number('idCompteCredit'), $in->number('montant'));
        });
```