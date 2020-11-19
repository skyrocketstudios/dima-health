<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorParamsProcessor extends UniteCreatorParamsProcessorWork{
	
	private static $arrPostTypeTaxCache = array();
	
	
	/**
	 * add other image thumbs based of the platform
	 */
	protected function addOtherImageData($data, $name, $imageID){
		
		if(empty($data))
			$data = array();
		
		$imageID = trim($imageID);
		if(is_numeric($imageID) == false)
			return($data);
		
		$post = get_post($imageID);
			
		if(empty($post))
			return($data);
					
		$title = UniteFunctionsWPUC::getAttachmentPostTitle($post);
		$caption = 	$post->post_excerpt;
		$description = 	$post->post_content;
		
		$alt = UniteFunctionsWPUC::getAttachmentPostAlt($imageID);
		
		if(empty($alt))
			$alt = $title;
		
		$data["{$name}_title"] = $title;
		$data["{$name}_alt"] = $alt;
		$data["{$name}_description"] = $description;
		$data["{$name}_caption"] = $caption;
		
		return($data);
	}
	
	
	/**
	 * add other image thumbs based of the platform
	 */
	protected function addOtherImageThumbs($data, $name, $imageID){
		
		if(empty($data))
			$data = array();
		
			
		$imageID = trim($imageID);
		if(is_numeric($imageID) == false)
			return($data);
		
		$arrSizes = UniteFunctionsWPUC::getArrThumbSizes();
		
		$urlFull = UniteFunctionsWPUC::getUrlAttachmentImage($imageID);
		
		foreach($arrSizes as $size => $sizeTitle){
			
			if(empty($size))
				continue;
			
			if($size == "full")
				continue;
			
			//change the hypen to underscore
			
			$thumbName = $name."_thumb_".$size;
			if($size == "medium")
				$thumbName = $name."_thumb";
			
			$thumbName = str_replace("-", "_", $thumbName);
			
			$urlThumb = UniteFunctionsWPUC::getUrlAttachmentImage($imageID, $size);
			if(empty($urlThumb))
				$urlThumb = $urlFull;
			
			if(!isset($data[$thumbName]))
				$data[$thumbName] = $urlThumb;
			
		}
		
		return($data);
	}
	
	
	/**
	 * get post data
	 */
	protected function getPostData($postID, $arrPostAdditions = null){
		
		if(empty($postID))
			return(null);
		
		$post = get_post($postID);
		
		if(empty($post))
			return(null);
		
		try{
						
			$arrData = $this->getPostDataByObj($post, $arrPostAdditions);
			
			//dmp($arrData);exit();
			
			return($arrData);
						
		}catch(Exception $e){
			return(null);
		}
		
	}

	
	/**
	 * modify terms array for output
	 */
	public function modifyArrTermsForOutput($arrTerms){
			
			if(empty($arrTerms))
				return(array());
				
			$arrOutput = array();
			
			$index = 0;
			foreach($arrTerms as $slug => $arrTerm){
				
				$item = array();
				
				$item["index"] = $index;
				$item["id"] = UniteFunctionsUC::getVal($arrTerm, "term_id");
				$item["slug"] = UniteFunctionsUC::getVal($arrTerm, "slug");
				$item["name"] = UniteFunctionsUC::getVal($arrTerm, "name");
				$item["description"] = UniteFunctionsUC::getVal($arrTerm, "description");
				$item["link"] = UniteFunctionsUC::getVal($arrTerm, "link");
				
				$index++;
				
				$current = UniteFunctionsUC::getVal($arrTerm, "iscurrent");
				
				$item["iscurrent"] = $current;
				
				$item["class_selected"] = "";
				if($current == true)
					$item["class_selected"] = "	uc-selected";
				
				if(isset($arrTerm["count"]))
					$item["num_posts"] = $arrTerm["count"];
				
				$arrOutput[] = $item;
			}
			
			return($arrOutput);
		}
	
	
	/**
	 * get post category taxonomy
	 */
	private function getPostCategoryTaxonomy($postType){
		
		if(isset(self::$arrPostTypeTaxCache[$postType]))
			return(self::$arrPostTypeTaxCache[$postType]);
		
		$taxonomy = "category";
		
		if($postType == "post" || $postType == "page")
			return($taxonomy);
			
		$arrTax = UniteFunctionsWPUC::getPostTypeTaxomonies($postType);
			
		if(!empty($arrTax))
			$taxonomy = UniteFunctionsUC::getFirstNotEmptyKey($arrTax);
		
		self::$arrPostTypeTaxCache[$postType] = $taxonomy;
		
		return($taxonomy);
	}
	
	
	/**
	 * get post category fields
	 * for single category
	 * choose category from list
	 */
	private function getPostCategoryFields($postID, $post){
		
		//choose right taxonomy
		$postType = $post->post_type;
				
		$taxonomy = $this->getPostCategoryTaxonomy($postType);
		
		if(empty($postID))
			return(array());
		
		$arrTerms = UniteFunctionsWPUC::getPostSingleTerms($postID, $taxonomy);
		
		//get single category
		if(empty($arrTerms))
			return(array());
		
		$arrCatsOutput = $this->modifyArrTermsForOutput($arrTerms);
		
		//get term data
		if(count($arrTerms) == 1){		//single
			$arrTermData = UniteFunctionsUC::getArrFirstValue($arrTerms);
		}else{		//multiple
		
			unset($arrTerms["uncategorized"]);
			
			$arrTermData = UniteFunctionsUC::getArrFirstValue($arrTerms);			
		}

		$catID = UniteFunctionsUC::getVal($arrTermData, "term_id");
		
		
		$arrCategory = array();
		$arrCategory["category_id"] = $catID;
		$arrCategory["category_name"] = UniteFunctionsUC::getVal($arrTermData, "name");
		$arrCategory["category_slug"] = UniteFunctionsUC::getVal($arrTermData, "slug");
		$arrCategory["category_link"] = UniteFunctionsUC::getVal($arrTermData, "link");
		$arrCategory["categories"] = $arrCatsOutput;

		
		return($arrCategory);
	}
	
	
	/**
	 * get post data
	 */
	private function getPostDataByObj($post, $arrPostAdditions = false){
		
		try{
			
			$arrPost = (array)$post;
			$arrData = array();
			
			$postID = UniteFunctionsUC::getVal($arrPost, "ID");
			
			$arrData["id"] = $postID;
			$arrData["title"] = UniteFunctionsUC::getVal($arrPost, "post_title");
			$arrData["alias"] = UniteFunctionsUC::getVal($arrPost, "post_name");
			$arrData["content"] = UniteFunctionsUC::getVal($arrPost, "post_content");
			$arrData["link"] = UniteFunctionsWPUC::getPermalink($post);
			
			
			//get intro
			$intro = UniteFunctionsUC::getVal($arrPost, "post_excerpt");
			
			if(empty($intro)){
				$intro = $arrData["content"];
				
				if(!empty($intro)){
					$intro = strip_tags($intro);
					$intro = UniteFunctionsUC::limitStringSize($intro, 100);
				}
			}
			
			$arrData["intro"] = $intro;			

			//put data
			$strDate = UniteFunctionsUC::getVal($arrPost, "post_date");
			$arrData["date"] = !empty($strDate)?strtotime($strDate):"";
			
			$featuredImageID = UniteFunctionsWPUC::getFeaturedImageID($postID);
			
			if(!empty($featuredImageID))
				$arrData = $this->getProcessedParamsValue_image($arrData, $featuredImageID, array("name"=>"image"));
			
			//add custom fields
			
			foreach($arrPostAdditions as $addition){
				
				switch($addition){
					case GlobalsProviderUC::POST_ADDITION_CUSTOMFIELDS:
						
						$arrCustomFields = UniteFunctionsWPUC::getPostCustomFields($postID);
						
						$arrData = array_merge($arrData, $arrCustomFields);
					break;
					case GlobalsProviderUC::POST_ADDITION_CATEGORY:
						
						$arrCategory = $this->getPostCategoryFields($postID, $post);
						
						//HelperUC::addDebug("Get Category For Post: $postID ", $arrCategory);
						
						$arrData = array_merge($arrData, $arrCategory);
						
					break;
				}
				
			}

			
		}catch(Exception $e){
			
			$message = $e->getMessage();
			HelperUC::addDebug("Get Post Exception: ($postID) ".$message);
			
			return(null);
		}
			
		return($arrData);
	}
	
	/**
	 * run custom query
	 */
	private function getPostListData_getCustomQueryFilters($args, $value, $name, $data){
		
		if(GlobalsUC::$isProVersion == false)
			return($args);
		
		$queryID = UniteFunctionsUC::getVal($value, "{$name}_queryid");
		$queryID = trim($queryID);
		
		if(empty($queryID))
			return($args);
		
		HelperUC::addDebug("applying custom args filter: $queryID");
		
		//pass the widget data
		$widgetData = $data;
		unset($widgetData[$name]);
		
		$args = apply_filters($queryID, $args, $widgetData);
		
		HelperUC::addDebug("args after custom query", $args);
		
		return($args);
	}
	
	
	/**
	 * get post list data custom from filters
	 */
	private function getPostListData_custom($value, $name, $processType, $param, $data){
		
		if(empty($value))
			return(array());
			
		if(is_array($value) == false)
			return(array());
				
		$filters = array();	
		
		$source = UniteFunctionsUC::getVal($value, "{$name}_source");
		
		$isRelatedPosts = $source == "related";
		if(is_single() == false)
			$isRelatedPosts = false;
		
		if($isRelatedPosts == true){
			
			$post = get_post();
			$postType = $post->post_type;
			
			$filters["posttype"] = $postType;

			//prepare terms string
			$arrTerms = UniteFunctionsWPUC::getPostTerms($post);
				
			$strTerms = "";
			
			foreach($arrTerms as $tax => $terms){
				
				foreach($terms as $term){
					$termID = UniteFunctionsUC::getVal($term, "term_id");
					$strTerm = "{$tax}--{$termID}";
					
					if(!empty($strTerms))
						$strTerms .= ",";
					
					$strTerms .= $strTerm;
				}
			}
			
			//add terms
			
			if(!empty($strTerms)){
				$filters["category"] = $strTerms;
				$filters["category_relation"] = "OR";				
			}
			
			$filters["exclude_current_post"] = true;
			
			
		}else{
			
			$postType = UniteFunctionsUC::getVal($value, "{$name}_posttype", "post");
			$filters["posttype"] = $postType;
			
			$category = UniteFunctionsUC::getVal($value, "{$name}_category");
			
			if(!empty($category))
				$filters["category"] = UniteFunctionsUC::getVal($value, "{$name}_category");
			
			$relation = UniteFunctionsUC::getVal($value, "{$name}_category_relation");
			if(!empty($relation) && !empty($category))
				$filters["category_relation"] = $relation;
			
		}
		
		
		$limit = UniteFunctionsUC::getVal($value, "{$name}_maxitems");
		
		$limit = (int)$limit;
		if($limit <= 0)
			$limit = 100;
		
		if($limit > 1000)
			$limit = 1000;

		//------ Exclude ---------
		
		$arrExcludeBy = UniteFunctionsUC::getVal($value, "{$name}_excludeby", array());
		if(is_string($arrExcludeBy))
			$arrExcludeBy = array($arrExcludeBy);
		
		if(is_array($arrExcludeBy) == false)
			$arrExcludeBy = array();
		
		foreach($arrExcludeBy as $excludeBy){
			if($excludeBy == "current_post")
				$filters["exclude_current_post"] = true;
		}
				
		$orderBy = UniteFunctionsUC::getVal($value, "{$name}_orderby");

		$filters["limit"] = $limit;
		$filters["orderby"] = $orderBy;
		$filters["orderdir"] = UniteFunctionsUC::getVal($value, "{$name}_orderdir1");
		
		if($orderBy == UniteFunctionsWPUC::SORTBY_META_VALUE || UniteFunctionsWPUC::SORTBY_META_VALUE_NUM){
			$filters["meta_key"] = UniteFunctionsUC::getVal($value, "{$name}_orderby_meta_key1");
		}
		
		
		//add debug for further use
		HelperUC::addDebug("Post Filters", $filters);
		
		
		//run custom query if available
		$args = UniteFunctionsWPUC::getPostsArgs($filters);
				
		HelperUC::addDebug("Posts Query", $args);

		$args = $this->getPostListData_getCustomQueryFilters($args, $value, $name, $data);
		
		$isWpmlExists = UniteCreatorWpmlIntegrate::isWpmlExists();
		if($isWpmlExists)
			$args["suppress_filters"] = false;
		
		$arrPosts = get_posts($args);
		
		HelperUC::addDebug("posts found: ".count($arrPosts));
		
		if(empty($arrPosts))
			$arrPosts = array();
		
		
		return($arrPosts);
	}
	
	/*
		global $wp_query;

		$query_vars = $wp_query->query_vars;

		$query_vars = apply_filters( 'elementor/theme/posts_archive/query_posts/query_vars', $query_vars );
				
		if ( $query_vars !== $wp_query->query_vars ) {
			$this->query = new \WP_Query( $query_vars );
		} else {
			$this->query = $wp_query;
		}

		Query_Control::add_to_avoid_list( wp_list_pluck( $this->query->posts, 'ID' ) );
	
	 */
	
	/**
	 * get current posts
	 */
	private function getPostListData_currentPosts($value, $name, $data){
		
		//add debug for further use
		HelperUC::addDebug("Getting Current Posts");
		
		global $wp_query;
		$currentQueryVars = $wp_query->query_vars;
		$currentQueryVars = apply_filters( 'elementor/theme/posts_archive/query_posts/query_vars', $currentQueryVars);
		
		$currentQueryVars = $this->getPostListData_getCustomQueryFilters($currentQueryVars, $value, $name, $data);
		
		$query = $wp_query;
		if($currentQueryVars !== $wp_query->query_vars)
			$query = new WP_Query( $currentQueryVars );
		
		HelperUC::addDebug("Query Vars", $currentQueryVars);
		
		$arrPosts = $query->posts;
		
		if(empty($arrPosts))
			$arrPosts = array();
		
		HelperUC::addDebug("Posts Found: ". count($arrPosts));
			
		return($arrPosts);
	}
	
	
	/**
	 * get post list data
	 */
	private function getPostListData($value, $name, $processType, $param, $data){
		
		if($processType != self::PROCESS_TYPE_OUTPUT && $processType != self::PROCESS_TYPE_OUTPUT_BACK)
			return(null);

		HelperUC::addDebug("getPostList values", $value);
		HelperUC::addDebug("getPostList param", $param);
		
		$source = UniteFunctionsUC::getVal($value, "{$name}_source");
		
		$arrPosts = array();
		
		if($source === "current"){
			
			$arrPosts = $this->getPostListData_currentPosts($value, $name, $data);
			
		}else{
						
			$arrPosts = $this->getPostListData_custom($value, $name, $processType, $param, $data);
			
			$filters = array();
			$arrPostsFromFilter = array();
			$arrPostsFromFilter = UniteProviderFunctionsUC::applyFilters("uc_filter_posts_list", $arrPostsFromFilter, $value, $filters);
			
			if(!empty($arrPostsFromFilter))
				$arrPosts = $arrPostsFromFilter;
		}
		
		if(empty($arrPosts))
			$arrPosts = array();
			
		$useCustomFields = UniteFunctionsUC::getVal($param, "use_custom_fields");
		$useCustomFields = UniteFunctionsUC::strToBool($useCustomFields);
		
		$useCategory = UniteFunctionsUC::getVal($param, "use_category");
		$useCategory = UniteFunctionsUC::strToBool($useCategory);
		
		$arrPostAdditions = HelperProviderUC::getPostDataAdditions($useCustomFields, $useCategory);
		
		HelperUC::addDebug("post additions", $arrPostAdditions);
		
		$arrData = array();
		foreach($arrPosts as $post){
			
			$arrData[] = $this->getPostDataByObj($post, $arrPostAdditions);
		}

		
		return($arrData);
	}
	
	
	/**
	 * get terms data
	 */
	protected function getWPTermsData($value, $name, $processType, $param){
				
		$postType = UniteFunctionsUC::getVal($value, $name."_posttype");
		$taxonomy =  UniteFunctionsUC::getVal($value, $name."_taxonomy");
		
		$orderBy =  UniteFunctionsUC::getVal($value, $name."_orderby");
		$orderDir =  UniteFunctionsUC::getVal($value, $name."_orderdir");
		
		$hideEmpty = UniteFunctionsUC::getVal($value, $name."_hideempty");
		
		$isHide = false;
		if($hideEmpty == "hide")
			$isHide = true;
		
		if(empty($postType)){
			$postType = "post";
			$taxonomy = "category";
		}
		
		$arrTerms = UniteFunctionsWPUC::getTerms($taxonomy, $orderBy, $orderDir, $isHide);
		
		$arrTerms = $this->modifyArrTermsForOutput($arrTerms);
		
		return($arrTerms);
	}
	
	
	/**
	 * get processe param data, function with override
	 */
	protected function getProcessedParamData($data, $value, $param, $processType){
		
		$type = UniteFunctionsUC::getVal($param, "type");
		$name = UniteFunctionsUC::getVal($param, "name");
		
		//special params
		switch($type){
			
			case UniteCreatorDialogParam::PARAM_POSTS_LIST:
			    $data[$name] = $this->getPostListData($value, $name, $processType, $param, $data);
			break;
			case UniteCreatorDialogParam::PARAM_POST_TERMS:
				$data[$name] = $this->getWPTermsData($value, $name, $processType, $param);
			break;
			default:
				$data = parent::getProcessedParamData($data, $value, $param, $processType);
			break;
		}
		
			
		return($data);
	}
	
	
	
	/**
	 * get param value, function for override, by type
	 * to get multiple values from one, as array
	 */
	public function getSpecialParamValue($paramType, $paramName, $value, $arrValues){
		
	    switch($paramType){
	        case UniteCreatorDialogParam::PARAM_POSTS_LIST:
	        case UniteCreatorDialogParam::PARAM_POST_TERMS:
	        case UniteCreatorDialogParam::PARAM_CONTENT:
	            
	            $paramArrValues = array();
	            $paramArrValues[$paramName] = $value;
	            
	            foreach($arrValues as $key=>$value){
	                if(strpos($key, $paramName."_") === 0)
	                    $paramArrValues[$key] = $value;
	            }
	            
	            $value = $paramArrValues;
	            	            
	        break;
	    }
	    
	    
	    return($value);
	}
	
	
	
}