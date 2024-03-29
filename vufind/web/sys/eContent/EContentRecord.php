<?php
/**
 * Table Definition for EContentRecord
 */
require_once 'DB/DataObject.php';
require_once 'DB/DataObject/Cast.php';
require_once dirname(__FILE__).'/EContentItem.php';
require_once dirname(__FILE__).'/../SolrDataObject.php';
require_once dirname(__FILE__).'/../../../classes/interfaces/IEContentRecord.php';
require_once dirname(__FILE__).'/../../../classes/Utils/RegularExpressions.php';
require_once dirname(__FILE__).'/../../../classes/econtentBySource/EcontentDetailsFactory.php';
require_once dirname(__FILE__).'/../../../classes/FileMarc/FileMarc.php';
require_once dirname(__FILE__).'/../../../classes/FileMarc/MarcSubField.php';
require_once dirname(__FILE__).'/../../../classes/FileMarc/MarcRecordFields.php';
require_once dirname(__FILE__).'/../../../classes/interfaces/IGenericRecord.php';
require_once dirname(__FILE__).'/../../../classes/interfaces/IMarcRecordFieldsReader.php';


class EContentRecord extends SolrDataObject implements IEContentRecord,IMarcRecordFieldsReader,IGenericRecord
{
	const prefixUnique = "econtentRecord";
	const notReviewed = 'Not Reviewed';
	const reviewApproved = 'Approved';
	const reviewRejected = 'Rejected';
	const briefRecord = 'Brief Record';
	
	const accesType_acs = 'acs';
	
	public $__table = 'econtent_record';    // table name
	public $id;                      //int(25)
	public $cover;                    //varchar(255)
	public $title;
	public $subTitle;
	public $author;
	public $author2;
	public $description;
	public $contents;
	public $subject;
	public $language;
	public $publisher;
	public $publishDate;
	public $edition;
	public $isbn;
	public $issn;
	public $upc;
	public $lccn;
	public $series;
	public $topic;
	public $genre;
	public $region;
	public $era;
	public $target_audience;
	public $date_added;
	public $date_updated;
	public $notes;
	public $ilsId;
	public $source;
	public $sourceUrl;
	public $externalId; //An external id for use in systems like OverDrive, 3M, etc.
	public $purchaseUrl;
	public $addedBy; //Id of the user who added the record or -1 for imported
	public $reviewedBy; //Id of a cataloging use who reviewed the item for consistency
	public $reviewStatus; //0 = unreviewed, 1=approved, 2=rejected, 3=brief
	public $reviewNotes;
	public $accessType;
	public $availableCopies;
	public $onOrderCopies;
	public $trialTitle;
	public $marcControlField;
	public $collection;
	public $literary_form_full;
	public $marcRecord;
	public $status; //'active', 'archived', or 'deleted'
	
	//No table fields
	public $insertToSolr = true;

	/* Static get */
	function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('EContentRecord',$k,$v); }

	
	public function getMarcString()
	{
		return $this->marcRecord;
	}
	
	public function getMarcRecordFieldReader(IMarcRecordFields $marcRecordFields = NULL)
	{
		if(!$marcRecordFields)
		{
			$object = new MarcRecordFields($this);
			return $object;
		}
		return $marcRecordFields;
	}
	
	public function getType()
	{
		return 'EcontentRecord';
	}
	
	public function getPermanentPath()
	{
		return '/EcontentRecord/'.$this->id;
	}
	
	public function getUniqueSystemID()
	{
		return self::prefixUnique.$this->id;
	}
	
	function keys() {
		return array('id', 'filename');
	}
	function cores(){
		return array('econtent');
	}

	function solrId(){
		return $this->recordtype() . $this->id;
	}
	function recordtype(){
		return 'econtentRecord';
	}
	function title(){
		return $this->title;
	}
	function format_category(){
		global $configArray;

		$formats = $this->format();
		$formatCategory = null;
		$formatCategoryMap = $this->getFormatCategoryMap();
		foreach ($formats as $format){
			if (array_key_exists($format, $formatCategoryMap)){
				$formatCategory = $formatCategoryMap[$format];
				break;
			}
		}
		if ($formatCategory != null){
			if (array_key_exists("*", $formatCategoryMap)){
				$formatCategory = $formatCategoryMap[$format];
				break;
			}else{
				if(isset($configArray['EContent']['formatCategory'])){
					return $configArray['EContent']['formatCategory'];
				}else{
					return 'EMedia';
				}
			}
		}
		return $formatCategory;
	}
	function keywords(){
		return $this->title . "\r\n" .
		$this->subTitle . "\r\n" .
		$this->author . "\r\n" .
		$this->author2 . "\r\n" .
		$this->description . "\r\n" .
		$this->subject . "\r\n" .
		$this->language . "\r\n" .
		$this->publisher . "\r\n" .
		$this->publishDate . "\r\n" .
		$this->edition . "\r\n" .
		$this->isbn . "\r\n" .
		$this->issn . "\r\n" .
		$this->upc . "\r\n" .
		$this->lccn . "\r\n" .
		$this->series . "\r\n" .
		$this->topic . "\r\n" .
		$this->genre . "\r\n" .
		$this->region . "\r\n" .
		$this->era . "\r\n" .
		$this->target_audience . "\r\n" .
		$this->notes . "\r\n" .
		$this->source . "\r\n";
	}
	function subject_facet(){
		return $this->getPropertyArray('subject');
	}
	function topic_facet(){
		return $this->getPropertyArray('topic');
	}
	function getObjectStructure(){
		global $configArray;
		$structure = array(
		'id' => array(
      'property'=>'id', 
      'type'=>'hidden', 
      'label'=>'Id', 
      'primaryKey'=>true,
      'description'=>'The unique id of the e-pub file.',
		  'storeDb' => true, 
			'storeSolr' => false, 
		),

		array(
			'property'=>'recordtype', 
			'type'=>'method', 
			'methodName'=>'recordtype', 
			'storeDb' => false, 
			'storeSolr' => true, 
		),
		'solrId' => array(
    	'property'=>'id', 
    	'type'=>'method', 
    	'methodName'=>'solrId', 
    	'storeDb' => false, 
    	'storeSolr' => true, 
		),
		'institution' => array(
    	'property'=>'institution', 
    	'type'=>'method', 
    	'methodName'=>'institution', 
    	'storeDb' => false, 
    	'storeSolr' => true, 
		),
		'building' => array(
    	'property'=>'building', 
    	'type'=>'method', 
    	'methodName'=>'building', 
    	'storeDb' => false, 
    	'storeSolr' => true, 
		),
		'title' => array(
		  'property' => 'title',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>255, 
		  'label' => 'Title',
		  'description' => 'The title of the item.',
		  'required'=> true,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'author' => array(
		  'property' => 'author',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'Author',
		  'description' => 'The primary author of the item or editor if the title is a compilation of other works.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'status' => array(
		  'property' => 'status',
		  'type' => 'enum',
		  'values' => array('active' => 'Active', 'archived' => 'Archived', 'deleted' => 'Deleted'),
		  'label' => 'Status',
		  'description' => 'The Current Status of the record.',
		  'required'=> true,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'accessType' => array(
      'property'=>'accessType', 
      'type'=>'enum',
		  'values' => EContentRecord::getValidAccessTypes(),
      'label'=>'Access Type', 
      'description'=>'The type of access control to apply to the record.',
      'storeDb' => true,
		  'storeSolr' => false,
		),
		'availableCopies' => array(
      'property'=>'availableCopies', 
      'type'=>'integer',
		  'label'=>'Available Copies', 
      'description'=>'The number of copies that have been purchased and are available to patrons.',
      'storeDb' => true,
		  'storeSolr' => false,
		),
		'onOrderCopies' => array(
      'property'=>'onOrderCopies', 
      'type'=>'integer',
		  'label'=>'Copies On Order', 
      'description'=>'The number of copies that have been purchased but are not available for usage yet.',
      'storeDb' => true,
		  'storeSolr' => false,
		),
		'trialTitle' => array(
		  'property' => 'trialTitle',
		  'type' => 'checkbox',
		  'label' => "Trial Title",
		  'description' => 'Whether or not the title was loaded on a trial basis or if it is a premanent acquisition.',
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'cover' => array(
		  'property' => 'cover',
		  'type' => 'image',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'cover',
		  'description' => 'The cover of the item.',
		  'storagePath' => $configArray['Site']['coverPath'] . '/original',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'collection' => array(
		  'property' => 'collection',
		  'type' => 'enum',
		  'values' => array_merge(array('' => 'Unknown'), EContentRecord::getCollectionValues()),
		  'label' => 'Collection',
		  'description' => 'The cover of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'collection_group' => array(
		  'property' => 'collection_group',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'language' => array(
		  'property' => 'language',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'Language',
		  'description' => 'The Language of the item.',
		  'required'=> true,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'literary_form_full' => array(
		  'property' => 'literary_form_full',
		  'label' => 'Literary Form',
		  'description' => 'The Literary Form of the item.',
		  'type' => 'enum',
		  'values' => array(
		    '' => 'Unknown',
		    'Fiction' => 'Fiction',
		    'Non Fiction' => 'Non Fiction',
		    'Novels' => 'Novels',
		    'Short Stories' => 'Short Stories',
		    'Poetry' => 'Poetry',
		    'Dramas' => 'Dramas',
		    'Essays' => 'Essays',
		    'Mixed Forms' => 'Mixed Forms',
		    'Humor, Satires, etc.' => 'Humor, Satires, etc.',
		    'Speeches' => 'Speeches',
		    'Letters' => 'Letters',
		),
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'author2' => array(
		  'property' => 'author2',
		  'type' => 'crSeparated',
		  'label' => 'Additional Authors',
      'rows'=>3,
      'cols'=>80,
		  'description' => 'The Additional Authors of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'description' => array(
		  'property' => 'description',
		  'type' => 'textarea',
		  'label' => 'Description',
      'rows'=>3,
      'cols'=>80,
		  'description' => 'A brief description of the file for indexing and display if there is not an existing record within the catalog.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'contents' => array(
		  'property' => 'contents',
		  'type' => 'textarea',
		  'label' => 'Table of Contents',
      'rows'=>3,
      'cols'=>80,
		  'description' => 'The table of contents for the record.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'econtentText' => array(
		  'property' => 'econtentText',
		  'type' => 'method',
		  'label' => 'Full text of the eContent',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'subject' => array(
		  'property' => 'subject',
		  'type' => 'crSeparated',
		  'label' => 'Subject',
      'rows'=>3,
      'cols'=>80,
		  'description' => 'The Subject of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'subject_facet' => array(
		  'property' => 'subject_facet',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'topic_facet' => array(
		  'property' => 'topic_facet',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),

		/*'format' => array(
		 'property' => 'format',
		 'type' => 'text',
		 'size' => 100,
		 'maxLength'=>100,
		 'label' => 'Format',
		 'description' => 'The Format of the item.',
		 'required'=> true,
		 'storeDb' => true,
		 'storeSolr' => true,
		 ),*/
		'format_category' => array(
		  'property' => 'format_category',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'format' => array(
		  'property' => 'format',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'econtent_device' => array(
		  'property' => 'econtent_device',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'publisher' => array(
		  'property' => 'publisher',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'Publisher',
		  'description' => 'The Publisher of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'publishDate' => array(
		  'property' => 'publishDate',
		  'type' => 'integer',
		  'size' => 4,
		  'maxLength' => 4, 
		  'label' => 'Publication Year',
		  'description' => 'The year the title was published.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'edition' => array(
		  'property' => 'edition',
		  'type' => 'crSeparated',
		  'rows'=>2,
			'cols'=>80, 
		  'label' => 'Edition',
		  'description' => 'The Edition of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'isbn' => array(
		  'property' => 'isbn',
		  'type' => 'crSeparated',
		  'rows'=>1,
			'cols'=>80, 
		  'label' => 'isbn',
		  'description' => 'The isbn of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'issn' => array(
		  'property' => 'issn',
		  'type' => 'crSeparated',
		  'rows'=>1,
			'cols'=>80, 
		  'label' => 'issn',
		  'description' => 'The issn of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'upc' => array(
		  'property' => 'upc',
			'type' => 'crSeparated',
			'rows'=>1,
			'cols'=>80, 
		  'label' => 'upc',
		  'description' => 'The upc of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'lccn' => array(
		  'property' => 'lccn',
			'type' => 'crSeparated',
		  'rows'=>1,
			'cols'=>80, 
		  'label' => 'lccn',
		  'description' => 'The lccn of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'series' => array(
		  'property' => 'series',
		  'type' => 'crSeparated',
		  'rows'=>3,
			'cols'=>80, 
		  'label' => 'series',
		  'description' => 'The Series of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'topic' => array(
		  'property' => 'topic',
		  'type' => 'crSeparated',
		  'rows'=>3,
			'cols'=>80, 
		  'label' => 'Topic',
		  'description' => 'The Topic of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'genre' => array(
		  'property' => 'genre',
		  'type' => 'crSeparated',
		  'rows'=>3,
			'cols'=>80, 
		  'label' => 'Genre',
		  'description' => 'The Genre of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'genre_facet' => array(
		  'property' => 'genre_facet',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'region' => array(
		  'property' => 'region',
		  'type' => 'crSeparated',
		  'rows'=>3,
			'cols'=>80,
		  'label' => 'Region',
		  'description' => 'The Region of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'geographic' => array(
			'property' => 'geographic',
			'type' => 'method',
			'storeDb' => false,
		  'storeSolr' => true,
		),
		'geographic_facet' => array(
			'property' => 'geographic_facet',
			'type' => 'method',
			'storeDb' => false,
		  'storeSolr' => true,
		),
		'era' => array(
		  'property' => 'era',
		  'type' => 'crSeparated',
		  'rows'=>3,
			'cols'=>80,
		  'label' => 'Era',
		  'description' => 'The Era of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'target_audience' => array(
		  'property' => 'target_audience',
		  'type' => 'enum',
		  'values' => array( 
		    '' => 'Unknown',
		    'Preschool (0-5)' => 'Preschool (0-5)',
		    'Primary (6-8)' => 'Primary (6-8)',
		    'Pre-adolescent (9-13)' => 'Pre-adolescent (9-13)',
		    'Adolescent (14-17)' => 'Adolescent (14-17)',
		    'Adult' => 'Adult',
		    'Easy Reader' => 'Easy Reader',
		    'Juvenile' => 'Juvenile',
		    'General Interest' => 'General Interest',
		    'Special Interest' => 'Special Interest',
		),
		  'label' => 'Target Audience',
		  'description' => 'The Target Audience of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => true,
		),
		'target_audience_full' => array(
		  'property' => 'target_audience_full',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'date_added' => array(
		  'property' => 'date_added',
		  'type' => 'hidden',
		  'label' => 'Date Added',
		  'description' => 'The Date Added.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'notes' => array(
		  'property' => 'notes',
		  'type' => 'textarea',
		  'label' => 'Notes',
      'rows'=>3,
      'cols'=>80,
		  'description' => 'The Notes on the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'ilsId' => array(
      'property'=>'ilsId', 
      'type'=>'text', 
      'label'=>'ilsId', 
      'primaryKey'=>true,
      'description'=>'The Id of the record within the ILS or blank if the record does not exist in the ILS.',
			'required' => false,
		  'storeDb' => true, 
			'storeSolr' => false, 
		),
		'source' => array(
		  'property' => 'source',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'Source',
		  'description' => 'The Source of the item.',
		  'required'=> true,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'sourceUrl' => array(
		  'property' => 'sourceUrl',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'Source Url',
		  'description' => 'The Source Url of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'purchaseUrl' => array(
		  'property' => 'purchaseUrl',
		  'type' => 'text',
		  'size' => 100,
		  'maxLength'=>100, 
		  'label' => 'Purchase Url',
		  'description' => 'The Purchase Url of the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'addedBy' => array(
				'property'=>'addedBy',
				'type'=>'hidden',
				'label'=>'addedBy',
				'description'=>'addedBy',
				'storeDb' => true,
				'storeSolr' => false
		),
		'reviewedBy' => array(
				'property'=>'reviewedBy',
				'type'=>'hidden',
				'label'=>'reviewedBy',
				'description'=>'reviewedBy',
				'storeDb' => true,
				'storeSolr' => false,
		),
		'reviewStatus' => array(
		  'property' => 'reviewStatus',
		  'type' => 'enum',
		  'values' => array('Not Reviewed' => self::notReviewed, 'Approved' => self::reviewApproved , 'Rejected' => self::reviewRejected, 'Brief Record' => self::briefRecord),
		  'label' => 'Review Status',
		  'description' => 'The Review Status of the item.',
		  'required'=> true,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'reviewNotes' => array(
		  'property' => 'reviewNotes',
		  'type' => 'textarea',
		  'label' => 'Review Notes',
      'rows'=>3,
      'cols'=>80,
		  'description' => 'The Review Notes on the item.',
		  'required'=> false,
		  'storeDb' => true,
		  'storeSolr' => false,
		),
		'keywords' => array(
		  'property' => 'keywords',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'format_boost' => array(
		  'property' => 'format_boost',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'format_boost' => array(
		  'property' => 'format_boost',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'language_boost' => array(
		  'property' => 'language_boost',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'num_holdings' => array(
		  'property' => 'num_holdings',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'available_at' => array(
		  'property' => 'available_at',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'bib_suppression' => array(
		  'property' => 'bib_suppression',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'rating' => array(
		  'property' => 'rating',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'rating_facet' => array(
		  'property' => 'rating_facet',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'allfields' => array(
		  'property' => 'allfields',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'title_sort' => array(
		  'property' => 'title_sort',
		  'type' => 'method',
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		'time_since_added' => array(
		  'property' => 'time_since_added',
		  'type' => 'method', 
		  'storeDb' => false,
		  'storeSolr' => true,
		),
		);

		return $structure;
	}
	static function getValidAccessTypes(){
		return array('free' => 'No Usage Restrictions', self::accesType_acs => 'Adobe Content Server', 'singleUse' => 'Single use per copy');
	}
	function institution(){
		$institutions = array();
		$items = $this->getItems(false);
		foreach ($items as $item){
			$libraryId = $item->libraryId;
			if ($libraryId == -1){
				$institutions[] = "Digital Collection";
			}else{
				$library = new Library();
				$library->libraryId = $libraryId;
				if ($library->find(true)){
					$institutions[] = $library->facetLabel;
				}else{
					$institutions[] = "Unknown";
				}
			}
		}
		return $institutions;
	}
	function building(){
		$institutions = array();
		$items = $this->getItems(false);
		foreach ($items as $item){
			$libraryId = $item->libraryId;
			if ($libraryId == -1){
				$institutions[] = "Digital Collection";
			}else{
				$library = new Library();
				$library->libraryId = $libraryId;
				if ($library->find(true)){
					$institutions[] = $library->facetLabel . ' Online';
				}else{
					$institutions[] = "Unknown";
				}
			}
		}
		return $institutions;
	}
	function title_sort(){
		$tmpTitle = $this->title;
		//Trim off leading words
		if (preg_match('/^((?:a|an|the|el|la|"|\')\\s).*$/i', $tmpTitle, $trimGroup)) {
			$partToTrim = $trimGroup[1];
			$tmpTitle = substr($tmpTitle, strlen($partToTrim));
		}
		return $tmpTitle;
	}
	function allfields(){
		$allFields = "";
		foreach ($this as $field => $value){

			if (!in_array($field, array('__table', 'items', 'N')) && strpos($field, "_") !== 0){
				//echo ("Processing $field\r\n<br/>");
				if (is_array($value)){
					foreach ($value as $val){
						if (strlen($val) > 0){
							$allFields .= " $val";
						}
					}
				}else if (strlen($value) > 0){
					$allFields .= " $value";
				}
			}
		}
		return trim($allFields);
	}
	function rating(){
		require_once 'sys/eContent/EContentRating.php';
		$econtentRating = new EContentRating();
		$query = "SELECT AVG(rating) as avgRating from econtent_rating where recordId = {$this->id}";
		$econtentRating->query($query);
		if ($econtentRating->N > 0){
			$econtentRating->fetch();
			if ($econtentRating->avgRating == 0){
				return -2.5;
			}else{
				return $econtentRating->avgRating;
			}

		}else{
			return -2.5;
		}

	}

	function rating_facet(){
		$rating = $this->rating();
		if ($rating > 4.5){
			return "fiveStar";
		}elseif ($rating > 3.5){
			return "fourStar";
		}elseif ($rating > 2.5){
			return "threeStar";
		}elseif ($rating > 1.5){
			return "twoStar";
		}elseif ($rating > 0.0001){
			return "oneStar";
		}else{
			return "Unrated";
		}
	}

	static function getCollectionValues(){
		return array(
		  'aebf' => 'Adult ebook fiction',
		  'aebnf' => 'Adult ebook nonfiction',
		  'eaeb' => 'Easy ebook (fiction & nonfiction)',
		  'jebf' => 'Juv. ebook fiction',
		  'jebnf' => 'Juv. ebook nonfiction',
		  'yebf' => 'Ya ebook fiction',
		  'yebnf' => 'Ya ebook nonfiction',

		  'aeaf' => 'Adult eaudio fiction',
		  'aeanf' => 'Adult eaudio nonfiction',
		  'eaea' => 'Easy eaudio (fiction & nonfiction)',
		  'jeaf' => 'Juv. eaudio fiction',
		  'jeanf' => 'Juv. eaudio nonfiction',
		  'yeaf' => 'Ya eaudio fiction',
		  'yeanf' => 'Ya eaudio nonfiction',

		  'aevf' => 'Adult evideo fiction',
		  'aevnf' => 'Adult evideo nonfiction',
		  'eaev' => 'Easy evideo (fiction & nonfiction)',
		  'jevf' => 'Juv. evideo fiction',
		  'jeavf' => 'Juv. evideo nonfiction',
		  'yevf' => 'Ya evideo fiction',
		  'yevnf' => 'Ya evideo nonfiction',

		  'aem' => 'Adult emusic',
		  'jem' => 'Juv. emusic',
		  'yem' => 'Ya emusic',
		);
	}
	function genre_facet(){
		return $this->genre;
	}
	function collection_group(){
		if (strlen($this->collection) > 0){
			require_once 'Drivers/DCL.php';
			$dcl = new DCL();
			return $dcl->translateCollection($this->collection);
		}else{
			return null;
		}
	}
	function bib_suppression(){
		if (!isset($this->status)){
			return "notsuppressed";
		}elseif ($this->status == 'active' || $this->status == 'archived'){
			return "notsuppressed";
		}else{
			return "suppressed";
		}
	}
	function available_at(){
		//Check to see if the item is checked out or if it has available holds
		if ($this->status == 'active'){
			require_once('Drivers/EContentDriver.php');
			if ($this->isOverDrive())
			{
				//TODO: Check to see if i really is available
				return array('OverDrive');
			}elseif ($this->source == 'Freegal'){
				return array('Freegal');
			}else{
				$driver = new EContentDriver();
				$holdings = $driver->getHolding($this->id);
				$statusSummary = $driver->getStatusSummary($this->id, $holdings);
				if ($statusSummary['availableCopies'] > 0){
					return array('Online');
				}else{
					return array();
				}
			}
		}else{
			return array();
		}
	}
	function target_audience_full(){
		if ($this->target_audience != null && strlen(trim($this->target_audience)) > 0){
			return $this->target_audience;
		}else{
			return null;
		}
	}
	function format_boost(){
		if ($this->status == 'active'){
			return 575;
		}else{
			return 0;
		}
	}
	function language_boost(){
		if ($this->status == 'active'){
			if ($this->language == 'English'){
				return 300;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	function num_holdings(){
		if ($this->status == 'active'){
			if (strcasecmp($this->source, 'OverDrive') == 0){
				return 1;
			}elseif ($this->accessType == 'free'){
				return 25;
			}else{
				return $this->availableCopies;
			}
		}else{
			return 0;
		}
	}

	function validateCover(){
		//Setup validation return array
		$validationResults = array(
      'validatedOk' => true,
      'errors' => array(),
		);

		if ($_FILES['cover']["error"] != 0 && $_FILES['cover']["error"] != 4){
			$validationResults['errors'][] = DataObjectUtil::getFileUploadMessage($_FILES['cover']["error"], 'cover' );
		}
			
		//Make sure there aren't errors
		if (count($validationResults['errors']) > 0){
			$validationResults['validatedOk'] = false;
		}
		return $validationResults;
	}

	function format(){
		$formats = array();
		//Load itmes for the record
		$items = $this->getItems(false);
		
		$detailsEcontent = EcontentDetailsFactory::get($this);
		if($detailsEcontent !== false)
		{
			return array($detailsEcontent->getFormats());
		}
		else
		{
			foreach ($items as $item)
			{
				$formatValue = translate($item->item_type);
				$formats[$formatValue] = $formatValue;
			}
		}
		return $formats;
	}

	/**
	 * Get a list of devices that this title should work on based on format.
	 */
	function econtent_device(){
		$formats = $this->format();
		$devices = array();
		$deviceCompatibilityMap = $this->getDeviceCompatibilityMap();
		foreach ($formats as $format){
			if (array_key_exists($format, $deviceCompatibilityMap)){
				$devices = array_merge($devices, $deviceCompatibilityMap[$format]);
			}
		}
		return $devices;
	}

	/**
	 * Get a list of all formats that are in the catalog with a list of devices that support that format.
	 * Information is stored in device_compatibility_map.ini with a format per line and devices that support
	 * the format separated by line.
	 */
	function getDeviceCompatibilityMap(){
		global $memcache;
		global $configArray;
		global $servername;
		$deviceMap = $memcache->get('device_compatibility_map');
		if ($deviceMap == false){
			$deviceMap = array();
			if (file_exists("../../sites/$servername/conf/device_compatibility_map.ini")){
				// Return the file path (note that all ini files are in the conf/ directory)
				$deviceMapFile = "../../sites/$servername/conf/device_compatibility_map.ini";
			}else{
				$deviceMapFile = "../../sites/default/conf/device_compatibility_map.ini";
			}
			$formatInformation = parse_ini_file($deviceMapFile);
			foreach ($formatInformation as $format => $devicesCsv){
				$devices = explode(",", $devicesCsv);
				$deviceMap[$format] = $devices;
			}
			$memcache->set('device_compatibility_map', $deviceMap, 0, $configArray['Caching']['device_compatibility_map']);
		}
		return $deviceMap;
	}

	/**
	 * Get a list of all formats that are in the catalog with a mapping to the correct category to use for the format.
	 * Information is stored in econtent_category_map.ini with a format per line and the category to use after it.
	 * Use a * to match any category
	 */
	function getFormatCategoryMap(){
		global $memcache;
		global $configArray;
		global $servername;
		$categoryMap = $memcache->get('econtent_category_map');
		if ($categoryMap == false){
			$categoryMap = array();
			if (file_exists("../../sites/$servername/conf/econtent_category_map.ini")){
				// Return the file path (note that all ini files are in the conf/ directory)
				$categoryMapFile = "../../sites/$servername/conf/econtent_category_map.ini";
			}else{
				$categoryMapFile = "../../sites/default/conf/econtent_category_map.ini";
			}
			$formatInformation = parse_ini_file($categoryMapFile);
			foreach ($formatInformation as $format => $category){
				$categoryMap[$format] = $category;
			}
			$memcache->set('econtent_category_map', $categoryMap, 0, $configArray['Caching']['econtent_category_map']);
		}
		return $categoryMap;
	}

	function econtentText(){
		$eContentText = "";
		if (!$this->_quickReindex && strcasecmp($this->source, 'OverDrive') != 0){
			//Load items for the record
			$items = $this->getItems();
			//Load full text of each item if possible
			foreach ($items as $item){
				$eContentText .= $item->getFullText();
			}
		}
		return $eContentText;
	}

	private $items = null;
	function getItems($reload = false)
	{
		if ($this->isOverDrive())
		{
			$this->items = $this->_getOverDriveItems($reload);
			return $this->items;
		}
		if ($this->items == null || $reload){
			$this->items = array();

			
			$eContentItem = new EContentItem();
			$eContentItem->recordId = $this->id;
			$eContentItem->find();
			while ($eContentItem->fetch()){
				$this->items[] = clone $eContentItem;
			}

			
		}
		return $this->items;
	}

	private function _getOverDriveItems($reload)
	{
		global $configArray;
		
		require_once dirname(__FILE__).'/../../../classes/services/OverDriveServices.php';
		require_once dirname(__FILE__).'/../../../classes/API/OverDrive/OverDriveServicesAPI.php';
		require_once dirname(__FILE__).'/../../../classes/API/OverDrive/OverDriveFormatTranslation.php';
		require_once dirname(__FILE__).'/OverdriveItem.php';
		
		$overDriveServices  = new OverDriveServices();
		$overDriveServicesAPI = new OverDriveServicesAPI();
		
		$marRecord = $this->getNormalizedMarcRecord();
		
		$regularExpressions = new RegularExpressions();
		
		$overDriveId = $regularExpressions->getFieldValueFromURL($this->getSourceUrl(), "ID");
		$availability = $overDriveServicesAPI->getItemAvailability($overDriveId);
		
		$item = new OverdriveItem();
		
		$item->availableCopies = $availability->copiesAvailable;
		$item->totalCopies = $availability->copiesOwned;
		$item->numHolds = $availability->numberOfHolds;
		$item->available = $availability->available;
		
		$metadata = $overDriveServicesAPI->getItemMetadata($overDriveId);
		
		$links = array();
		$samples = array();
		if(!empty($metadata->formats))
		{
			$i=0;
			$j=0;
			foreach ($metadata->formats as $format)
			{				
				$formatId = OverDriveFormatTranslation::getFormatIdFromString($format->id);
				if($formatId != -1)
				{
					if($item->available)
					{
						$links[$i] = array(
								'onclick' => "return checkoutOverDriveItem('".$overDriveId."','".$formatId."' );",
								'text' => 'Check Out',
								'action' => 'CheckOut'
								);
					}
					else
					{
						$links[$i] = array(
								'onclick' => "return placeOverDriveHold('".$overDriveId."','".$formatId."' );",
								'text' => 'Place Hold',
								'action' => 'Hold'
								);
					}
				
					$links[$i]['overDriveId'] = $overDriveId;
					$links[$i]['formatId'] = $format->id;
					$links[$i]['format'] = $format->name;
					$links[$i]['fileSize'] = $format->fileSize;
				
					if(!empty($format->samples))
					{
						$i=0;
						foreach ($format->samples as $sample)
						{
							$samples[$j]['title'] = $sample->source;
							$samples[$j]['url'] = $sample->url;
							$samples[$j]['type'] = OverDriveFormatTranslation::getMediaTypeFromUrl($sample->url);
					
							$j++;
						}
					}
					$i++;
				}
			}
		}
		$item->links = $links;
		$item->samples = $samples;
		$item->libraryId = -1;
		
		return array($item);
	}

	function getNumItems()
	{
		if ($this->items == null){
			$this->items = array();
			if ($this->isOverDrive())
			{
				return -1;
			}
			else
			{
				require_once 'sys/eContent/EContentItem.php';
				$eContentItem = new EContentItem();
				$eContentItem->recordId = $this->id;
				return $eContentItem->find();
			}
		}
		return count($this->items);
	}

	function validateEpub(){
		//Setup validation return array
		$validationResults = array(
      'validatedOk' => true,
      'errors' => array(),
		);

		//Check to see if we have an existing file
		if (isset($_REQUEST['filename_existing']) && $_FILES['filename']['error'] != 4){
			if ($_FILES['filename']["error"] != 0){
				$validationResults['errors'][] = DataObjectUtil::getFileUploadMessage($_FILES['filename']["error"], 'filename' );
			}

			//Make sure that the epub is unique, the title for the object should already be filled out.
			$query = "SELECT * FROM epub_files WHERE filename='" . mysql_escape_string($this->filename) . "' and id != '{$this->id}'";
			$result = mysql_query($query);
			if (mysql_numrows($result) > 0){
				//The title is not unique
				$validationResults['errors'][] = "This file has already been uploaded.  Please select another name.";
			}

			if ($this->type == 'epub'){
				if ($_FILES['filename']['type'] != 'application/epub+zip' && $_FILES['filename']['type'] != 'application/octet-stream'){
					$validationResults['errors'][] = "It appears that the file uploaded is not an EPUB file.  Please upload a valid EPUB without DRM.  Detected {$_FILES['filename']['type']}.";
				}
			}else if ($this->type == 'pdf'){
				if ($_FILES['filename']['type'] != 'application/pdf'){
					$validationResults['errors'][] = "It appears that the file uploaded is not a PDF file.  Please upload a valid PDF without DRM.  Detected {$_FILES['filename']['type']}.";
				}
			}
		}else{
			//Using the existing file.
		}

		//Make sure there aren't errors
		if (count($validationResults['errors']) > 0){
			$validationResults['validatedOk'] = false;
		}
		return $validationResults;
	}

	function insert()
	{
		$ret =  $this->insertDetailed($this->insertToSolr);
		if ($ret){
			$this->clearCachedCover();
		}

		return $ret;
	}

	function update(){
		//Check to see if we are adding copies.
		//If so, we wil need to process the hold queue after
		//The tile is saved
		$currentValue = new EContentRecord();
		$currentValue->id = $this->id;
		$currentValue->find(true);

		$ret = parent::update();
		if ($ret){
			$this->clearCachedCover();
			if ($currentValue->N == 1 && $currentValue->availableCopies != $this->availableCopies){
				require_once 'Drivers/EContentDriver.php';
				$eContentDriver = new EContentDriver();
				$eContentDriver->processHoldQueue($this->id);
			}
		}
		return $ret;
	}
	private function clearCachedCover()
	{
		global $configArray;

		//Clear the cached bookcover if one has been added.
		$logger = new Logger();
		if (isset($this->cover) && (strlen($this->cover) > 0)){
			//Call via API since bookcovers may be on a different server
			$url = $configArray['Site']['coverUrl'] . '/API/ItemAPI?method=clearBookCoverCacheById&id=econtentRecord' . $this->id;
			$logger->log("Clearing cached cover: $url", PEAR_LOG_DEBUG );
			file_get_contents($url);
		}else{
			$logger->log("Record {$this->id} does not have cover ({$this->cover}), not clearing cache", PEAR_LOG_DEBUG );
		}
	}
	
	public function getPropertyArray($propertyName){
		$propertyValue = $this->$propertyName;
		if (strlen($propertyValue) == 0){
			return array();
		}else{
			return explode("\r\n", $propertyValue);
		}
	}
	
	public function getIsbn(){
		require_once 'sys/ISBN.php';
		$isbns = $this->getPropertyArray('isbn');
		if (count($isbns) == 0){
			return null;
		}else{
			$isbn = ISBN::normalizeISBN($isbns[0]);
			return $isbn;
		}
	}
	public function getIsbn10(){
		require_once 'sys/ISBN.php';
		$isbn = $this->getIsbn();
		if ($isbn == null){
			return $isbn;
		}elseif(strlen($isbn == 10)){
			return $isbn;
		}else{
			require_once 'Drivers/marmot_inc/ISBNConverter.php';
			return ISBNConverter::convertISBN13to10($isbn);
		}
	}
	public function getUpc(){
		$upcs = $this->getPropertyArray('upc');
		if (count($upcs) == 0){
			return null;
		}else{
			return $upcs[0];
		}
	}
	public function geographic(){
		return $this->region;
	}
	public function geographic_facet(){
		return $this->getPropertyArray('region');
	}
	public function delete(){
		//Delete any items that are associated with the record
		if (strcasecmp($this->source, 'OverDrive') != 0){
			$items = $this->getItems();
			foreach ($items as $item){
				$item->delete();
			}
		}
		parent::delete();
	}
	public function time_since_added(){
		return '';
	}
	public function getOverDriveId()
	{
		$overdriveUrl = $this->sourceUrl;
		if ($overdriveUrl == null || strlen($overdriveUrl) < 36){
			return null;
		}else{
			$overdriveUrl = preg_replace('/[&|?]Format=\d+/i', '', $overdriveUrl);
			$overdriveUrl = preg_replace('/[{}]/i', '', $overdriveUrl);
			return substr($overdriveUrl, -36);
		}
	}
	
	public function getNormalizedMarcRecord()
	{
		$marcString = trim($this->marcRecord);		
		$marcString = preg_replace('/#29;/', "\x1D",  $marcString);
		$marcString = preg_replace('/#30;/', "\x1E",  $marcString);
		$marcString = preg_replace('/#31;/', "\x1F",  $marcString);
		$marcString = preg_replace('/#163;/', "\xA3", $marcString);
		$marcString = preg_replace('/#169;/', utf8_encode("\xA9"), $marcString);
		$marcString = preg_replace('/#174;/', "\xAE", $marcString);
		$marcString = preg_replace('/#230;/', "\xE6", $marcString);
		
		return $marcString;
	}
	
	public function isGutenberg()
	{
		if ($this->getsource() == 'Gutenberg')
		{
			return true;
		}
		return false;
	}
	
	public function isFreegal()
	{
		if ($this->getsource() == 'Freegal')
		{
			return true;
		}
		return false;
	}
	
	public function is3M()
	{
		if ($this->getsource() == '3M')
		{
			return true;
		}
		return false;
	}
	
	public function isOverDrive()
	{
		
		switch ($this->getsource())
		{
			case "OverDrive":
			case "OverDriveAPI":
				return true;
				break;
			default:
				return false;
				break;
		}
	}
	
	public function hasMarcRecord()
	
	{
		switch ($this->getsource())
		{
			case "Freegal":
			case "OverDriveAPI":
				return false;
				break;
			default:
				if(empty($this->marcRecord))
				{
					return false;
				}
				return true;
				break;
		}
	}
	
	public function isBriefRecord()
	{
		if($this->reviewStatus == self::briefRecord)
		{
			return true;
		}
		return false;
	}
	
	
	public function isFree()
	{
		if ($this->accessType != self::accesType_acs)
		{
			return true;
		}
		return false;
	}
	
	public function isACS()
	{
		if ($this->accessType == self::accesType_acs)
		{
			return true;
		}
		return false;
	}
	
	public function getTitle(IMarcRecordFields $marcRecordFields = NULL)
	{
		$title = $this->title;
		if(empty($title))
		{
			$title = $this->getMarcRecordFieldReader($marcRecordFields)->getTitle();
		}
		return $title;
	}
	
	public function getAuthor(IMarcRecordFields $marcRecordFields = NULL)
	{
		$author = $this->author;
		if(empty($author))
		{
			$author = $this->getMarcRecordFieldReader($marcRecordFields)->getAuthor();
		}
		return $author;
	}
	
	public function getSourceUrl(IMarcRecordFields $marcRecordFields = NULL)
	{
		$sourceUrl = $this->sourceUrl;
		if(empty($sourceUrl))
		{
			$sourceUrl = $this->getMarcRecordFieldReader($marcRecordFields)->getSourceUrl();
		}
		return $sourceUrl;
	}
	
	public function getYear(IMarcRecordFields $marcRecordFields = NULL)
	{
		return $this->getMarcRecordFieldReader($marcRecordFields)->getYear();
	}
	
	public function getPublicationPlace(IMarcRecordFields $marcRecordFields = NULL)
	{
		return $this->getMarcRecordFieldReader($marcRecordFields)->getPublicationPlace();
	}
	
	public function getEAN(){return '';}
	public function getSecondaryAuthor(){return $this->getAuthor2();}
	
	public function getShelfMark(){return '';}

	//setters and getters
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCover(){
		return $this->cover;
	}

	public function setCover($cover){
		$this->cover = $cover;
	}

	

	public function setTitle($title){
		$this->title = $title;
	}

	public function getSubtitle(){
		return $this->subtitle;
	}

	public function setSubtitle($subtitle){
		$this->subtitle = $subtitle;
	}

	

	public function setAuthor($author){
		$this->author = $author;
	}

	public function getAuthor2(){
		return $this->author2;
	}

	public function setAuthor2($author2){
		$this->author2 = $author2;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getContents(){
		return $this->contents;
	}

	public function setContents($contents){
		$this->contents = $contents;
	}

	public function getSubject(){
		return $this->subject;
	}

	public function setSubject($subject){
		$this->subject = $subject;
	}

	public function getLanguage(){
		return $this->language;
	}

	public function setLanguage($language){
		$this->language = $language;
	}

	public function getPublisher(){
		return $this->publisher;
	}

	public function setPublisher($publisher){
		$this->publisher = $publisher;
	}

	public function getPublishdate(){
		return $this->publishdate;
	}

	public function setPublishdate($publishdate){
		$this->publishdate = $publishdate;
	}

	public function getEdition(){
		return $this->edition;
	}

	public function setEdition($edition){
		$this->edition = $edition;
	}

	public function getISSN(){
		return $this->issn;
	}

	public function setISSN($issn){
		$this->issn = $issn;
	}

	public function getlccn(){
		return $this->lccn;
	}

	public function setlccn($lccn){
		$this->lccn = $lccn;
	}

	public function getSeries(){
		return $this->series;
	}

	public function setSeries($series){
		$this->series = $series;
	}

	public function getTopic(){
		return $this->topic;
	}

	public function setTopic($topic){
		$this->topic = $topic;
	}

	public function getGenre(){
		return $this->genre;
	}

	public function setGenre($genre){
		$this->genre = $genre;
	}

	public function getRegion(){
		return $this->region;
	}

	public function setRegion($region){
		$this->region = $region;
	}

	public function getEra(){
		return $this->era;
	}

	public function setEra($era){
		$this->era = $era;
	}

	public function gettarget_audience(){
		return $this->target_audience;
	}

	public function settarget_audience($target_audience){
		$this->target_audience = $target_audience;
	}

	public function getdate_added(){
		return $this->date_added;
	}

	public function setdate_added($date_added){
		$this->date_added = $date_added;
	}

	public function getdate_updated(){
		return $this->date_updated;
	}

	public function setdate_updated($date_updated){
		$this->date_updated = $date_updated;
	}

	public function getnotes(){
		return $this->notes;
	}

	public function setnotes($notes){
		$this->notes = $notes;
	}

	public function getilsid(){
		return $this->ilsid;
	}

	public function setilsid($ilsid){
		$this->ilsid = $ilsid;
	}

	public function getsource(){
		return $this->source;
	}

	public function setsource($source){
		$this->source = $source;
	}

	public function setsourceurl($sourceurl){
		$this->sourceUrl = $sourceurl;
	}

	public function getpurchaseurl(){
		return $this->purchaseurl;
	}

	public function setpurchaseurl($purchaseurl){
		$this->purchaseurl = $purchaseurl;
	}

	public function getaddedby(){
		return $this->addedby;
	}

	public function setaddedby($addedby){
		$this->addedby = $addedby;
	}

	public function getreviewedby(){
		return $this->reviewedby;
	}

	public function setreviewedby($reviewedby){
		$this->reviewedby = $reviewedby;
	}

	public function getreviewstatus(){
		return $this->reviewstatus;
	}

	public function setreviewstatus($reviewstatus){
		$this->reviewstatus = $reviewstatus;
	}

	public function getreviewnotes(){
		return $this->reviewnotes;
	}

	public function setreviewnotes($reviewnotes){
		$this->reviewnotes = $reviewnotes;
	}

	public function getaccesstype(){
		return $this->accesstype;
	}

	public function setaccesstype($accesstype){
		$this->accesstype = $accesstype;
	}

	public function getavailablecopies(){
		return $this->availablecopies;
	}

	public function setavailablecopies($availablecopies){
		$this->availablecopies = $availablecopies;
	}

	public function getonordercopies(){
		return $this->onordercopies;
	}

	public function setonordercopies($onordercopies){
		$this->onordercopies = $onordercopies;
	}

	public function gettrialtitle(){
		return $this->trialtitle;
	}

	public function settrialtitle($trialtitle){
		$this->trialtitle = $trialtitle;
	}

	public function getmarccontrolfield(){
		return $this->marccontrolfield;
	}

	public function setmarccontrolfield($marccontrolfield){
		$this->marccontrolfield = $marccontrolfield;
	}

	public function getcollection(){
		return $this->collection;
	}

	public function setcollection($collection){
		$this->collection = $collection;
	}

	public function getliterary_form_full(){
		return $this->literary_form_full;
	}

	public function setliterary_form_full($literary_form_full){
		$this->literary_form_full = $literary_form_full;
	}

	public function getmarcrecord(){
		return $this->marcrecord;
	}

	public function setmarcrecord($marcrecord){
		$this->marcrecord = $marcrecord;
	}

	public function getstatus(){
		return $this->status;
	}

	public function setstatus($status){
		$this->status = $status;
	}
}