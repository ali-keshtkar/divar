<?php

namespace Modules\Seo\Http\Traits;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Lang;

trait HasSeo
{
    #region Properties

    /**
     * Module namespace for translation.
     *
     * @var string
     */
    private string $seo_namespace;

    /**
     * Module page for translation.
     *
     * @var string
     */
    private string $seo_page;

    #endregion

    #region Getter and setters

    /**
     * Get seo namespace for translation.
     *
     * @return string
     */
    private function getSeoNamespace(): string
    {
        return $this->seo_namespace;
    }

    /**
     * Set seo namespace for translation.
     *
     * @param string $seo_namespace
     */
    private function setSeoNamespace(string $seo_namespace): void
    {
        $this->seo_namespace = $seo_namespace;
    }

    /**
     * Get seo page for translation.
     *
     * @return string
     */
    private function getSeoPage(): string
    {
        return $this->seo_page;
    }

    /**
     * Set seo page for translation.
     *
     * @param string $seo_page
     */
    private function setSeoPage(string $seo_page): void
    {
        $this->seo_page = $seo_page;
    }

    #endregion

    #region Private Methods

    /**
     * Generate seo page.
     *
     * @return void
     */
    private function _generateSeoPage()
    {
        $title = $this->_getTitle();
        $description = $this->_getDescription();
        SEOTools::setTitle($title)->setDescription($description);
        SEOTools::twitter()->setTitle($title)->setDescription($description);
        SEOTools::opengraph()->setTitle($title)->setDescription($description);
    }

    /**
     * Get translated title seo page.
     * @return string
     */
    private function _getTitle(): string
    {
        return Lang::get($this->getSeoNamespace() . '::page.' . $this->getSeoPage() . '.seo.title');
    }

    /**
     * Get translated description seo page.
     *
     * @return string
     */
    private function _getDescription(): string
    {
        return Lang::get($this->getSeoNamespace() . '::page.' . $this->getSeoPage() . '.seo.description');
    }

    /**
     * Initialize seo page details.
     *
     * @param string $namespace
     * @param string $page
     * @return $this
     */
    private function initSeo(string $namespace, string $page): static
    {
        $this->setSeoNamespace($namespace);
        $this->setSeoPage($page);
        return $this;
    }

    #endregion
}
