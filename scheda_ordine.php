<?php
include 'libs/db.php';

// lettura parametro da URL
$idOrdine = $_GET['id'];

$ordine = recuperaOrdine($idOrdine);
$dettagliOrdine = recuperaDettagliOrdine($idOrdine);

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
          <h1>Scheda ordine <strong><?=$ordine['id']?></strong></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <dl class="dl-horizontal">
                <dt>Cliente</dt>
                <dd><?=$ordine["nome"] . ' ' . $ordine["cognome"]?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Data</dt>
                <dd><?=$ordine["data"]?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Totale</dt>
                <dd><?=$ordine["totale"] / 100?> &euro;</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Spedizione</dt>
                <dd><?=$ordine["stato"]?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Cambia lo stato</dt>
                <dd><button>In consegna</button></dd>
                <dd><button>Consegnato</button></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Note</dt>
                <dd><?=$ordine["note"]?></dd>
            </dl>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <h1>Dettagli ordine</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Codice prodotto</th>
                  <th>Prezzo</th>
                  <th>Quantità</th>
                  <th>Totale</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach($dettagliOrdine as $dettaglio) {
                ?>
                <tr>
                  <td><?=$dettaglio["codice_prodotto"]?></td>
                  <td><?=$dettaglio["prezzo"] / 100?> &euro;</td>
                  <td><?=$dettaglio["quantita"]?></td>
                  <td><?=$dettaglio["totale"] / 100?> &euro;</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
        </div>
      </div>

    </main>
    <?php include 'include/footer.php'; ?>
  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</html>
