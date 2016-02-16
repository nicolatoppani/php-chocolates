<?php

function aggiungiSceltaCategoria($categoria) {
  // costruzione array che rappresenta riga carrello
  $_SESSION['preferenza'] = $categoria;

  // inizializzazione preferenza
//  if (isset($_SESSION['preferenza'])) {
//    $scelta = $_SESSION['preferenza'];

    // carrello già inizializzato?
    // verifica che lo stesso prodotto non sia già presente nel carrello
    // in caso affermativo, aggiornarne la quantità

//  } else {
//    $scelta = 'Tutte';
//  }

  // aggiunta riga a scelta
//  $scelta = $categoria;

  // aggiornamento sessione
//  $_SESSION['preferenza'] = $scelta;
}

function getSceltaCategoria() {
  if (isset($_SESSION['preferenza'])) {
    return $_SESSION['preferenza'];
  } else {
    return $_SESSION['preferenza'] = 'Tutte';
  }
}
