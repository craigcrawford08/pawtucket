<div class="thumbnailCaption">
<?php
	$vs_caption = "";
	if($this->getVar('caption_title')){
		$vs_caption .= "<i>";
		$vs_caption .= (unicode_strlen($this->getVar('caption_title')) > 30) ? preg_replace('![^A-Za-z0-9]+$!', '', substr(strip_tags($this->getVar('caption_title')), 0, 30)).'...' : $this->getVar('caption_title');
		$vs_caption .= "</i><br/>";
	}
	if($this->getVar('caption_entities')){
		$vs_caption .= (unicode_strlen($this->getVar('caption_entities')) > 30) ? substr($this->getVar('caption_entities'), 0, 30).'...' : $this->getVar('caption_entities');
		$vs_caption .= "<br/>";
	}
	if($this->getVar('caption_date_list')){
		$vs_caption .= (unicode_strlen($this->getVar('caption_date_list')) > 30) ? substr($this->getVar('caption_date_list'), 0, 30).'...' : $this->getVar('caption_date_list');
		$vs_caption .= "<br/>";
	}
	if($this->getVar('caption_object_type')){
		$vs_caption .= (unicode_strlen($this->getVar('caption_object_type')) > 30) ? substr(unicode_ucfirst($this->getVar('caption_object_type')), 0, 30).'...' : unicode_ucfirst($this->getVar('caption_object_type'));
	}
	print caNavLink($this->request, $vs_caption, '', 'Detail', 'Object', 'Show', array('object_id' => $this->getVar("object_id")));
?>
</div>