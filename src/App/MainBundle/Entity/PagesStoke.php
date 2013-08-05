<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PagesStoke
 *
 * @ORM\Table(name="pages_stoke")
 * @ORM\Entity
 */
class PagesStoke
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param int $cacheable
     */
    public function setCacheable($cacheable)
    {
        $this->cacheable = $cacheable;
    }

    /**
     * @return int
     */
    public function getCacheable()
    {
        return $this->cacheable;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param boolean $contentDispo
     */
    public function setContentDispo($contentDispo)
    {
        $this->contentDispo = $contentDispo;
    }

    /**
     * @return boolean
     */
    public function getContentDispo()
    {
        return $this->contentDispo;
    }

    /**
     * @param string $contenttype
     */
    public function setContenttype($contenttype)
    {
        $this->contenttype = $contenttype;
    }

    /**
     * @return string
     */
    public function getContenttype()
    {
        return $this->contenttype;
    }

    /**
     * @param int $createdby
     */
    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;
    }

    /**
     * @return int
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * @param int $createdon
     */
    public function setCreatedon($createdon)
    {
        $this->createdon = $createdon;
    }

    /**
     * @return int
     */
    public function getCreatedon()
    {
        return $this->createdon;
    }

    /**
     * @param int $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param int $deletedby
     */
    public function setDeletedby($deletedby)
    {
        $this->deletedby = $deletedby;
    }

    /**
     * @return int
     */
    public function getDeletedby()
    {
        return $this->deletedby;
    }

    /**
     * @param int $deletedon
     */
    public function setDeletedon($deletedon)
    {
        $this->deletedon = $deletedon;
    }

    /**
     * @return int
     */
    public function getDeletedon()
    {
        return $this->deletedon;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param boolean $donthit
     */
    public function setDonthit($donthit)
    {
        $this->donthit = $donthit;
    }

    /**
     * @return boolean
     */
    public function getDonthit()
    {
        return $this->donthit;
    }

    /**
     * @param int $editedby
     */
    public function setEditedby($editedby)
    {
        $this->editedby = $editedby;
    }

    /**
     * @return int
     */
    public function getEditedby()
    {
        return $this->editedby;
    }

    /**
     * @param int $editedon
     */
    public function setEditedon($editedon)
    {
        $this->editedon = $editedon;
    }

    /**
     * @return int
     */
    public function getEditedon()
    {
        return $this->editedon;
    }

    /**
     * @param boolean $haskeywords
     */
    public function setHaskeywords($haskeywords)
    {
        $this->haskeywords = $haskeywords;
    }

    /**
     * @return boolean
     */
    public function getHaskeywords()
    {
        return $this->haskeywords;
    }

    /**
     * @param boolean $hasmetatags
     */
    public function setHasmetatags($hasmetatags)
    {
        $this->hasmetatags = $hasmetatags;
    }

    /**
     * @return boolean
     */
    public function getHasmetatags()
    {
        return $this->hasmetatags;
    }

    /**
     * @param boolean $hidemenu
     */
    public function setHidemenu($hidemenu)
    {
        $this->hidemenu = $hidemenu;
    }

    /**
     * @return boolean
     */
    public function getHidemenu()
    {
        return $this->hidemenu;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $introtext
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;
    }

    /**
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * @param int $isfolder
     */
    public function setIsfolder($isfolder)
    {
        $this->isfolder = $isfolder;
    }

    /**
     * @return int
     */
    public function getIsfolder()
    {
        return $this->isfolder;
    }

    /**
     * @param string $linkAttributes
     */
    public function setLinkAttributes($linkAttributes)
    {
        $this->linkAttributes = $linkAttributes;
    }

    /**
     * @return string
     */
    public function getLinkAttributes()
    {
        return $this->linkAttributes;
    }

    /**
     * @param string $longtitle
     */
    public function setLongtitle($longtitle)
    {
        $this->longtitle = $longtitle;
    }

    /**
     * @return string
     */
    public function getLongtitle()
    {
        return $this->longtitle;
    }

    /**
     * @param int $menuindex
     */
    public function setMenuindex($menuindex)
    {
        $this->menuindex = $menuindex;
    }

    /**
     * @return int
     */
    public function getMenuindex()
    {
        return $this->menuindex;
    }

    /**
     * @param string $menutitle
     */
    public function setMenutitle($menutitle)
    {
        $this->menutitle = $menutitle;
    }

    /**
     * @return string
     */
    public function getMenutitle()
    {
        return $this->menutitle;
    }

    /**
     * @param string $pagetitle
     */
    public function setPagetitle($pagetitle)
    {
        $this->pagetitle = $pagetitle;
    }

    /**
     * @return string
     */
    public function getPagetitle()
    {
        return $this->pagetitle;
    }

    /**
     * @param int $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param boolean $privatemgr
     */
    public function setPrivatemgr($privatemgr)
    {
        $this->privatemgr = $privatemgr;
    }

    /**
     * @return boolean
     */
    public function getPrivatemgr()
    {
        return $this->privatemgr;
    }

    /**
     * @param boolean $privateweb
     */
    public function setPrivateweb($privateweb)
    {
        $this->privateweb = $privateweb;
    }

    /**
     * @return boolean
     */
    public function getPrivateweb()
    {
        return $this->privateweb;
    }

    /**
     * @param int $pubDate
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * @return int
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * @param int $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return int
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param int $publishedby
     */
    public function setPublishedby($publishedby)
    {
        $this->publishedby = $publishedby;
    }

    /**
     * @return int
     */
    public function getPublishedby()
    {
        return $this->publishedby;
    }

    /**
     * @param int $publishedon
     */
    public function setPublishedon($publishedon)
    {
        $this->publishedon = $publishedon;
    }

    /**
     * @return int
     */
    public function getPublishedon()
    {
        return $this->publishedon;
    }

    /**
     * @param boolean $richtext
     */
    public function setRichtext($richtext)
    {
        $this->richtext = $richtext;
    }

    /**
     * @return boolean
     */
    public function getRichtext()
    {
        return $this->richtext;
    }

    /**
     * @param int $searchable
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;
    }

    /**
     * @return int
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param int $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return int
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $unpubDate
     */
    public function setUnpubDate($unpubDate)
    {
        $this->unpubDate = $unpubDate;
    }

    /**
     * @return int
     */
    public function getUnpubDate()
    {
        return $this->unpubDate;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=50, nullable=false)
     */
    private $contenttype;

    /**
     * @var string
     *
     * @ORM\Column(name="pagetitle", type="string", length=255, nullable=false)
     */
    private $pagetitle;

    /**
     * @var string
     *
     * @ORM\Column(name="longtitle", type="string", length=255, nullable=false)
     */
    private $longtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="link_attributes", type="string", length=255, nullable=false)
     */
    private $linkAttributes;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="pub_date", type="integer", nullable=false)
     */
    private $pubDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="unpub_date", type="integer", nullable=false)
     */
    private $unpubDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent", type="integer", nullable=false)
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="isfolder", type="integer", nullable=false)
     */
    private $isfolder;

    /**
     * @var string
     *
     * @ORM\Column(name="introtext", type="text", nullable=true)
     */
    private $introtext;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="richtext", type="boolean", nullable=false)
     */
    private $richtext;

    /**
     * @var integer
     *
     * @ORM\Column(name="template", type="integer", nullable=false)
     */
    private $template;

    /**
     * @var integer
     *
     * @ORM\Column(name="menuindex", type="integer", nullable=false)
     */
    private $menuindex;

    /**
     * @var integer
     *
     * @ORM\Column(name="searchable", type="integer", nullable=false)
     */
    private $searchable;

    /**
     * @var integer
     *
     * @ORM\Column(name="cacheable", type="integer", nullable=false)
     */
    private $cacheable;

    /**
     * @var integer
     *
     * @ORM\Column(name="createdby", type="integer", nullable=false)
     */
    private $createdby;

    /**
     * @var integer
     *
     * @ORM\Column(name="createdon", type="integer", nullable=false)
     */
    private $createdon;

    /**
     * @var integer
     *
     * @ORM\Column(name="editedby", type="integer", nullable=false)
     */
    private $editedby;

    /**
     * @var integer
     *
     * @ORM\Column(name="editedon", type="integer", nullable=false)
     */
    private $editedon;

    /**
     * @var integer
     *
     * @ORM\Column(name="deleted", type="integer", nullable=false)
     */
    private $deleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="deletedon", type="integer", nullable=false)
     */
    private $deletedon;

    /**
     * @var integer
     *
     * @ORM\Column(name="deletedby", type="integer", nullable=false)
     */
    private $deletedby;

    /**
     * @var integer
     *
     * @ORM\Column(name="publishedon", type="integer", nullable=false)
     */
    private $publishedon;

    /**
     * @var integer
     *
     * @ORM\Column(name="publishedby", type="integer", nullable=false)
     */
    private $publishedby;

    /**
     * @var string
     *
     * @ORM\Column(name="menutitle", type="string", length=255, nullable=false)
     */
    private $menutitle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="donthit", type="boolean", nullable=false)
     */
    private $donthit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="haskeywords", type="boolean", nullable=false)
     */
    private $haskeywords;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hasmetatags", type="boolean", nullable=false)
     */
    private $hasmetatags;

    /**
     * @var boolean
     *
     * @ORM\Column(name="privateweb", type="boolean", nullable=false)
     */
    private $privateweb;

    /**
     * @var boolean
     *
     * @ORM\Column(name="privatemgr", type="boolean", nullable=false)
     */
    private $privatemgr;

    /**
     * @var boolean
     *
     * @ORM\Column(name="content_dispo", type="boolean", nullable=false)
     */
    private $contentDispo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hidemenu", type="boolean", nullable=false)
     */
    private $hidemenu;


}
