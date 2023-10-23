<?php
namespace App\Traits\Model;

trait AlterModelAttributeTrait
{
    /**
     * ----------------------------------------------
     * Function to update the model's hidden property
     * ----------------------------------------------
     */
    public function hidden(array $hidden) : void
    {
        $this->hidden = $hidden ;
    }

    /**
     * ---------------------------------------------
     * Function to update the model's casts property
     * ---------------------------------------------
     */
    public function casts(array $casts) : void
    {
        $this->casts = $casts ;
    }
}
