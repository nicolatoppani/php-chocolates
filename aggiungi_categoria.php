<?php
// inizializziamo le sessioni
session_start();

include 'libs/db.php';
include 'libs/categoria.php';

unset($_SESSION['preferenza']);

// recuperiamo la categoria
// attraverso lettura parametro da URL
$categoria = $_GET['categoria'];

aggiungiSceltaCategoria($categoria);

//print_r($_SESSION); per verificare se la sessione è settata correttamente
// rimando a pagina prodotti
header ('location: prodotti.php');
