<?php
/* ----------------------------------------------------------------------
 * pawtucket2/themes/default/views/ca_places_detail_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2009-2010 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
	$t_place 		= $this->getVar('t_item');
	$vn_place_id 	= $t_place->getPrimaryKey();
	
	$vs_title 			= $this->getVar('label');
	
	$t_rel_types 		= $this->getVar('t_relationship_types');
	$va_access_values	= $this->getVar('access_values');

if (!$this->request->isAjax()) {
?>
	<div id="detailBody">
		<div id="pageNav">
<?php
			
			if ($this->getVar('previous_id')) {
				print caNavLink($this->request, "&lsaquo; "._t("Previous"), '', 'Detail', 'Place', 'Show', array('place_id' => $this->getVar('previous_id')), array('id' => 'previous'));
			}else{
				print "&lsaquo; "._t("Previous");
			}
			print "&nbsp;&nbsp;&nbsp;";
			print ResultContext::getResultsLinkForLastFind($this->request, 'ca_places', _t("Back"), '');
			print "&nbsp;&nbsp;&nbsp;";
			if ($this->getVar('next_id') > 0) {
				print caNavLink($this->request, _t("Next")." &rsaquo;", '', 'Detail', 'Place', 'Show', array('place_id' => $this->getVar('next_id')), array('id' => 'next'));
			}else{
				print _t("Next")." &rsaquo;";
			}
?>
		</div><!-- end nav -->
		<h1><?php print unicode_ucfirst($t_place->getTypeName()).': '.$vs_title; ?></h1>
		<div id="leftCol">	
<?php
			# --- identifier
			if($t_place->get('idno')){
				print "<div class='unit'><b>"._t("Identifier")."</b>: ".$t_place->get('idno')."</div><!-- end unit -->";
			}
			# --- attributes
			$va_attributes = $this->request->config->get('ca_places_detail_display_attributes');
			if(is_array($va_attributes) && (sizeof($va_attributes) > 0)){
				foreach($va_attributes as $vs_attribute_code){
					if($t_place->get("ca_places.".$vs_attribute_code)){
						print "<div class='unit'><b>".$t_place->getAttributeLabel($vs_attribute_code).":</b> ".$t_place->get("ca_places.".$vs_attribute_code)."</div><!-- end unit -->";
					}
				}
			}
			# --- description
			if($this->request->config->get('ca_places_description_attribute')){
				if($vs_description_text = $t_place->get("ca_places.".$this->request->config->get('ca_places_description_attribute'))){
					print "<div class='unit'><div id='description'><b>".$t_place->getAttributeLabel($this->request->config->get('ca_places_description_attribute')).":</b> ".$vs_description_text."</div></div><!-- end unit -->";				
?>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#description').expander({
								slicePoint: 300,
								expandText: '<?php print _t('[more]'); ?>',
								userCollapse: false
							});
						});
					</script>
<?php
				}
			}
			# --- entities
			$va_entities = $t_place->get("ca_entities", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_entities) > 0){	
?>
				<div class="unit"><h2><?php print _t("Related")." ".((sizeof($va_entities) > 1) ? _t("Entities") : _t("Entity")); ?></h2>
<?php
				foreach($va_entities as $va_entity) {
					print "<div>".(($this->request->config->get('allow_detail_for_ca_entities')) ? caNavLink($this->request, $va_entity["label"], '', 'Detail', 'Entity', 'Show', array('entity_id' => $va_entity["entity_id"])) : $va_entity["label"])." (".$va_entity['relationship_typename'].")</div>";
				}
?>
				</div><!-- end unit -->
<?php
			}
			
			# --- occurrences
			$va_occurrences = $t_place->get("ca_occurrences", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			$va_sorted_occurrences = array();
			if(sizeof($va_occurrences) > 0){
				$t_occ = new ca_occurrences();
				$va_item_types = $t_occ->getTypeList();
				foreach($va_occurrences as $va_occurrence) {
					$t_occ->load($va_occurrence['occurrence_id']);
					$va_sorted_occurrences[$va_occurrence['item_type_id']][$va_occurrence['occurrence_id']] = $va_occurrence;
				}
				
				foreach($va_sorted_occurrences as $vn_occurrence_type_id => $va_occurrence_list) {
?>
						<div class="unit"><h2><?php print _t("Related")." ".$va_item_types[$vn_occurrence_type_id]['name_singular'].((sizeof($va_occurrence_list) > 1) ? "s" : ""); ?></h2>
<?php
					foreach($va_occurrence_list as $vn_rel_occurrence_id => $va_info) {
						print "<div>".(($this->request->config->get('allow_detail_for_ca_occurrences')) ? caNavLink($this->request, $va_info["label"], '', 'Detail', 'Occurrence', 'Show', array('occurrence_id' => $vn_rel_occurrence_id)) : $va_info["label"])." (".$va_info['relationship_typename'].")</div>";
					}
					print "</div><!-- end unit -->";
				}
			}
			# --- places
			$va_places = $t_place->get("ca_places", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_places) > 0){
				print "<div class='unit'><h2>"._t("Related Place").((sizeof($va_places) > 1) ? "s" : "")."</h2>";
				foreach($va_places as $va_place_info){
					print "<div>".(($this->request->config->get('allow_detail_for_ca_places')) ? caNavLink($this->request, $va_place_info['label'], '', 'Detail', 'Place', 'Show', array('place_id' => $va_place_info['place_id'])) : $va_place_info['label'])." (".$va_place_info['relationship_typename'].")</div>";
				}
				print "</div><!-- end unit -->";
			}
			# --- collections
			$va_collections = $t_place->get("ca_collections", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_collections) > 0){
				print "<div class='unit'><h2>"._t("Related Collection").((sizeof($va_collections) > 1) ? "s" : "")."</h2>";
				foreach($va_collections as $va_collection_info){
					print "<div>".(($this->request->config->get('allow_detail_for_ca_collections')) ? caNavLink($this->request, $va_collection_info['label'], '', 'Detail', 'Collection', 'Show', array('collection_id' => $va_collection_info['collection_id'])) : $va_collection_info['label'])." (".$va_collection_info['relationship_typename'].")</div>";
				}
				print "</div><!-- end unit -->";
			}
			# --- vocabulary terms
			$va_terms = $t_place->get("ca_list_items", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_terms) > 0){
				print "<div class='unit'><H2>"._t("Subject").((sizeof($va_terms) > 1) ? "s" : "")."</H2>";
				foreach($va_terms as $va_term_info){
					print "<div>";
					print caNavLink($this->request, $va_term_info['label'], '', '', 'Search', 'Index', array('search' => $va_term_info['label']));
					print "</div>";
				}
				print "</div><!-- end unit -->";
			}		
			
?>
	</div><!-- end leftCol -->
			
	<div id="rightCol">
		<div id="resultBox">
<?php
}
		// set parameters for paging controls view
		$this->setVar('other_paging_parameters', array(
			'place_id' => $vn_place_id
		));
			
		print $this->render('related_objects_grid.php');
		
if (!$this->request->isAjax()) {
?>
		</div><!-- end resultBox -->


	</div><!-- end rightCol -->
</div><!-- end detailBody -->
<?php
}
?>