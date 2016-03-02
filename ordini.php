<?php
include 'libs/db.php';
?>
<!DOCTYPE html>
<html>
  <head>
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
      <div class="row">
        <div class="col-md-12">
          <h1>Riepilogo ordini ricevuti</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <?php
          $ordini = getListaOrdini();
          if (count($ordini) > 0) { ?>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Num. prodotti</th>
                <th>Totale</th>
                <th>Stato spedizione</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($ordini as $ordine) {
              ?>
              <tr>
                <th scope="row"><?=$ordine["id"]?></th>
                <td><?=$ordine["data"]?></td>
                <td><?=$ordine["nome"] . ' ' . $ordine["cognome"]?></td>
                <td><?=$ordine["num_prodotti"]?></td>
                <td><?=$ordine["totale"] / 100?> &euro;</td>
                <td><?=$ordine["stato"]?></td>
                <td><a href="scheda_ordine.php?id=<?=$ordine["id"]?>" class="btn btn-link">dettaglio</a></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php } else { ?>
              Nessun ordine ricevuto fin'ora
          <?php } ?>
        </div>
      </div>
    </main>
    <?php include 'include/footer.php'; ?>
  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</html>
