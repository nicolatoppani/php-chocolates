<?php

use MvLabs\Chocosite\Entity\Tavoletta;

require __DIR__ . '/../vendor/autoload.php';

function creaConnessionePDO() {
    // Nella realtà evitare di connettersi al db con l'utente "root".
    // E' preferibile creare un utente ad-hoc
    return new PDO('mysql:host=localhost;dbname=mvchocolates', 'root', 'mvlabs');
}

function inizializzaListaProdotti() {
    $db = creaConnessionePDO();
    $stmt =  $db->prepare('SELECT * FROM prodotti');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_CLASS, Tavoletta::class);
}

function recuperaProdottoDaCodice($codice) {
    $db = creaConnessionePDO();

    // prepara la query da eseguire
    $stmt = $db->prepare('SELECT * FROM prodotti WHERE codice LIKE :codice');

    // filtra i dati ricevuti e si assicura che non contengano caratteri indesiderati
    $codice = filter_input(INPUT_GET, 'codice', FILTER_SANITIZE_STRING);

    // sanitizza i dati per evitare SQL injections
    $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, Tavoletta::class);

    // esegue la query
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_CLASS);
}

function salvaOrdine($prodotti, $utente) {

    $db = creaConnessionePDO();

    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();

        // inserimento in tabella clienti
        $stmt = $db->prepare("INSERT INTO clienti (nome, cognome, email, indirizzo, citta, cap, provincia)
                              VALUES (:nome, :cognome, :email, :indirizzo, :citta, :cap, :provincia)");

        $stmt->bindParam(':nome', $utente['nome'], PDO::PARAM_STR);
        $stmt->bindParam(':cognome', $utente['cognome'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $utente['email'], PDO::PARAM_STR);
        $stmt->bindParam(':indirizzo', $utente['indirizzo'], PDO::PARAM_STR);
        $stmt->bindParam(':citta', $utente['citta'], PDO::PARAM_STR);
        $stmt->bindParam(':cap', $utente['cap'], PDO::PARAM_STR);
        $stmt->bindParam(':provincia', $utente['provincia'], PDO::PARAM_STR);

        $stmt->execute();

        $idCliente = $db->lastInsertId();

        // inserimento in tabella ordini
        $stmt = $db->prepare("INSERT INTO ordini (cliente_id, data, totale, note)
                              VALUES (:cliente_id, :data, :totale, :note)");

        $stmt->bindParam(':cliente_id', $idCliente, PDO::PARAM_INT);

        $date = date('Y-m-d H:i:s');
        $stmt->bindParam(':data', $date);

        $totale = 0;
        foreach($prodotti as $rigaProdotto) {
            $totale += $rigaProdotto['prodotto']->prezzo() * $rigaProdotto['quantita'];
        }

        $stmt->bindParam(':totale', $totale, PDO::PARAM_INT);
        $stmt->bindParam(':note', $utente['note'], PDO::PARAM_STR);

        $stmt->execute();

        $idOrdine = $db->lastInsertId();

        // inserimento in tabella ordini_dettagli
        foreach($prodotti as $rigaProdotto) {

            $stmt = $db->prepare("INSERT INTO ordini_dettagli (ordine_id, codice_prodotto, prezzo, quantita, totale)
                                  VALUES (:ordine_id, :codice_prodotto, :prezzo, :quantita, :totale)");

            $stmt->bindParam(':ordine_id', $idOrdine, PDO::PARAM_INT);
            $stmt->bindParam(':codice_prodotto', $rigaProdotto['prodotto']->codice(), PDO::PARAM_STR);
            $stmt->bindParam(':prezzo', $rigaProdotto['prodotto']->prezzo(), PDO::PARAM_INT);
            $stmt->bindParam(':quantita', $rigaProdotto['quantita'], PDO::PARAM_INT);

            $totale = $rigaProdotto['prodotto']->prezzo() * $rigaProdotto['quantita'];
            $stmt->bindParam(':totale', $totale, PDO::PARAM_INT);

            $stmt->execute();

        }

        $db->commit();

    } catch (Exception $e) {
        $db->rollBack();
        echo "Si è verificato un errore: " . $e->getMessage();
    }

    // svuotamento variabili di sessione
    unset($_SESSION['utente']);
    unset($_SESSION['carrello']);
}

function getListaOrdini() {
    $db = creaConnessionePDO();

    $query = "SELECT ordini.id, ordini.data, clienti.nome, clienti.cognome, COUNT(ordini_dettagli.id) as num_prodotti, ordini.totale, spedizione.stato
              FROM ordini, clienti, ordini_dettagli, spedizione
              WHERE ordini.id = ordini_dettagli.ordine_id
              AND ordini.cliente_id = clienti.id
              AND spedizione.id = ordini.stato_spedizione
              GROUP BY ordini.id";

    return $db->query($query);
}


function recuperaOrdine($id) {
    $db = creaConnessionePDO();

    $stmt = $db->prepare('SELECT * FROM ordini, clienti, spedizione WHERE ordini.cliente_id = clienti.id AND ordini.id = :id AND spedizione.id = ordini.stato_spedizione');

    $codice = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function recuperaDettagliOrdine($id) {
    $db = creaConnessionePDO();

    $stmt = $db->prepare('SELECT * FROM ordini_dettagli WHERE ordine_id = :id');

    $codice = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
