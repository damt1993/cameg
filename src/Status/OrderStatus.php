<?php

namespace App\Enum;

Enum OrderStatus: string
{
  case Pending = "pending";
  case Validate = "validate";
  case Delete = "delete";

  public function getLabel(): string
  {
    return match($this){
      self::Pending => "En cours",
      self::Validate => "Validé",
      self::Delete => "Supprimé",
    };
  }
}

