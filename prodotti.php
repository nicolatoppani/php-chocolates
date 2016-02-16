<?php

session_start();

include 'libs/db.php';
include 'libs/categoria.php';

$preferenza = getSceltaCategoria();
$arrayCategorie = inizializzaListaCategorie ();
$arrayProdotti = inizializzaListaProdottiCategorizzati($preferenza);

?>
<!DOCTYPE html>
<html>
  <head>
    <!--<p><?php echo "$preferenza";?></p>-->
    <title>MV chocosite</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
  </head>
  <body>
    <?php include 'include/header.php'; ?>
    <main>
      <div class="container-fluid">
        <div class="row banner-home">
          <div class="col-md-3">
            <img src="https://c2.staticflickr.com/6/5105/5626762538_406270541c_b.jpg">
          </div>
          <div class="col-md-9">
            <h1>Tutti i prodotti</h1>
            <h3 style="margin-top: 20px">Scegli il tuo preferito</h3>
            <a href="aggiungi_categoria.php?categoria=Tutte" type="button" class="btn <?php if ($preferenza == Tutte) echo "btn-info"; else echo "btn-default"; ?>">Tutte</a>
            <?php foreach($arrayCategorie as $gruppo): ?>
              <a href="aggiungi_categoria.php?categoria=<?= $gruppo['id']?>" role="button" class="btn <?php if ($gruppo['id'] == $preferenza) echo "btn-info"; else echo "btn-default"; ?>"><?= $gruppo['nome'] ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="row">
          <?php foreach($arrayProdotti as $prodotto) { ?>
          <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading"><a href="/prodotto.php?codice=<?= $prodotto['codice'] ?>"><?= $prodotto['nome'] ?></a></div>
              <div class="panel-body">
                <img src="<?= $prodotto['url_immagine'] ?>" />
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </main>
    <?php include 'include/footer.php'; ?>
  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</html>
