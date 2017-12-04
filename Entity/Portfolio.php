<?php

namespace Websolutio\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Portfolio
 */
class Portfolio
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $lead;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \Websolutio\BlogBundle\Entity\PortCategory
     */
    private $portcategory;

    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Portfolio
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Portfolio
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set lead
     *
     * @param string $lead
     *
     * @return Portfolio
     */
    public function setLead($lead)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead
     *
     * @return string
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Portfolio
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Portfolio
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Portfolio
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Portfolio
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set portcategory
     *
     * @param \Websolutio\BlogBundle\Entity\PortCategory $portcategory
     *
     * @return Portfolio
     */
    public function setPortcategory(\Websolutio\BlogBundle\Entity\PortCategory $portcategory = null)
    {
        $this->portcategory = $portcategory;

        return $this;
    }

    /**
     * Get portcategory
     *
     * @return \Websolutio\BlogBundle\Entity\PortCategory
     */
    public function getPortcategory()
    {
        return $this->portcategory;
    }
    
    // for file upload
    public $file;
    

    protected function getUploadDir()
    {
    return 'uploads/portfolio';
    }
 
    protected function getUploadRootDir()
    {
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
 
    public function getWebPath()
    {
    return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }
 
    public function getAbsolutePath()
    {
    return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }
    
    
    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {	
			$this->file = $file;
			// check if we have an old image path
			if (isset($this->image)) {
				// store the old name to delete after the update
				$this->temp = $this->image;
				$this->image = null;
			// !important! if new account, and img do not exists set image to null (as not exists)	
			} elseif (!isset($this->image)) {
				//$this->image = 'initial';
				// det image to null as image not exist yet
				$this->image = 'initial';
			}   
    }    

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // Add your code here
        if (null !== $this->file) {
        // do whatever you want to generate a unique name
        $this->image = uniqid().'.'.$this->file->guessExtension();
        }
    }
      
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return ;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $dimage = $this->file;
        list($width, $height) = getimagesize($dimage);

	    $newWidth = 480;
        $newHeight = ($height / $width) * $newWidth;
        
        $srcImg = imagecreatefromstring(file_get_contents($this->file));
        $destImg = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        $this->getFile()->move($this->getUploadRootDir(), $this->image);
        
        $savePath = $this->getUploadRootDir().'/'.$this->image;
        $imageQuality =99;
        
        imagejpeg($destImg, $savePath, $imageQuality);
        imagedestroy($srcImg);
        imagedestroy($destImg);
        unset($this->file);
		      
		// check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;

    }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
        // Add your code here
        if ($image = $this->getAbsolutePath()) {
        unlink($image);
        }
    }
    
    /**
     * @var boolean
     */
    private $linkactive;


    /**
     * Set linkactive
     *
     * @param boolean $linkactive
     *
     * @return Portfolio
     */
    public function setLinkactive($linkactive)
    {
        $this->linkactive = $linkactive;

        return $this;
    }

    /**
     * Get linkactive
     *
     * @return boolean
     */
    public function getLinkactive()
    {
        return $this->linkactive;
    }
    /**
     * @var boolean
     */
    private $publish;


    /**
     * Set publish
     *
     * @param boolean $publish
     *
     * @return Portfolio
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish
     *
     * @return boolean
     */
    public function getPublish()
    {
        return $this->publish;
    }
}
