<?php

/**
 * Génération d'une pagination
 */

namespace App\UI;

use Illuminate\Pagination\LengthAwarePaginator as Pagination;

final class PaginationRendering {

    /**
     * Objet Pagination
     * @var Pagination
     */
    private Pagination $pagination;

    /**
     * Constructeur
     * @param array $items Liste des éléments de la page cpurante
     * @param int $totalItems Nombre total des éléments
     * @param int $itemsPerPage Nombre d'éléments par page
     * @param int $currentPage Numéro de la page courante
     * @param string $queryPageKeyName Index dans GET du numéro de la page 
     */
    public function __construct(
        private array $items,
        private int $totalItems,
        private int $itemsPerPage,
        private int $currentPage,
        private string $queryPageKeyName = 'page'
    ) {
        $this->pagination = new Pagination($items, $totalItems, $itemsPerPage, $currentPage, [
			'path'	=> app('request')->url(),
		]);
		$this->pagination->setPageName($queryPageKeyName);
		$this->pagination->appends(request()->except($queryPageKeyName));
    }

    /**
     * Retourne le rendu de la pagination
     * @return string
     */
    public function __toString() : string
    {
        return $this->pagination->__toString();
    }
}