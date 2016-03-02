<?php
namespace MvLabs\Chocosite\Model;

interface archivio
{
  public function recupera($id = null);

  public function salva(\StdClass $object);

}
 ?>
