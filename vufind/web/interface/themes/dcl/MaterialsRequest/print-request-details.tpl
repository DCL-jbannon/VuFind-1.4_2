<strong><u>User Information</u></strong><br/>
<strong>Username:</strong> {$requestUser->firstname} {$requestUser->lastname}<br/>
<strong>Barcode:</strong> {$requestUser->cat_username}<br/>
{if $materialsRequest->phone}
  <strong>Phone Number:</strong> {$materialsRequest->phone}<br/>
{/if}
<strong>Email:</strong> {$materialsRequest->email}<br/>
{if $materialsRequest->illItem == 1}
  <strong>ILL if not purchased:</strong> Yes<br/>
{/if}
{if $materialsRequest->placeHoldWhenAvailable == 1}
  <strong>Place Hold for User:</strong> Yes ({$materialsRequest->location}{if $materialsRequest->bookmobileStop}{$materialsRequest->bookmobileStop}{/if})<br/>
{/if}
<br/>
<strong><u>Basic Information</u></strong><br/>
<strong>Format:</strong> {$materialsRequest->format}<br/>
<strong>Title:</strong> {$materialsRequest->title}<br/>
{if $materialsRequest->format == 'dvd' || $materialsRequest->format == 'vhs'}
<strong>Season:</strong> {$materialsRequest->season}<br/>
{/if}
{if $materialsRequest->format == 'dvd' || $materialsRequest->format == 'vhs'}
<strong>Actor / Director:</strong>
{elseif $materialsRequest->format == 'cdMusic'}
<strong>Artist / Composer:</strong>
{else}
<strong>Author:</strong>
{/if}
{$materialsRequest->author}<br/>
{if $materialsRequest->format == 'article'}
<strong>Magazine/Journal Title:</strong> {$materialsRequest->magazineTitle}<br/>
<strong>Date:</strong> {$materialsRequest->magazineDate}<br/>
<strong>Volume:</strong> {$materialsRequest->magazineVolume}<br/>
<strong>Number:</strong> {$materialsRequest->magazineNumber}<br/>
<strong>Page Numbers:</strong> {$materialsRequest->magazinePageNumbers}<br/>
{/if}
{if $materialsRequest->format == 'ebook'}
<strong>E-book format:</strong> {$materialsRequest->ebookFormat|translate}<br/>
{/if}
{if $materialsRequest->format == 'eaudio'}
<strong>E-audio format:</strong> {$materialsRequest->eaudioFormat|translate}<br/>
{/if}
<strong><u>Identifiers</u></strong><br/>
{if $materialsRequest->isbn}
  <strong>ISBN:</strong> {$materialsRequest->isbn}<br/>
{/if}
{if $materialsRequest->upc}
  <strong>UPC:</strong> {$materialsRequest->upc}<br/>
{/if}
{if $materialsRequest->issn}
  <strong>ISSN:</strong> {$materialsRequest->issn}<br/>
{/if}
{if $materialsRequest->oclcNumber}
  <strong>OCLC Number:</strong> {$materialsRequest->oclcNumber}<br/>
{/if}
<strong><u>Supplemental Details</u></strong><br/>
{if $materialsRequest->ageLevel}
  <strong>Age Level:</strong> {$materialsRequest->ageLevel}<br/>
{/if}
{if $materialsRequest->abridged != 2}
  <strong>Abridged:</strong> {if $materialsRequest->abridged == 1}Abridged Version{elseif $materialsRequest->abridged == 0}Unabridged Version{/if}<br/>
{/if}
{if $materialsRequest->bookType}
  <strong>Type:</strong> {$materialsRequest->bookType|translate|ucfirst}<br/>
{/if}
{if $materialsRequest->publisher}
  <strong>Publisher:</strong> {$materialsRequest->publisher}<br/>
{/if}
{if $materialsRequest->publicationYear}
  <strong>Publication Year:</strong> {$materialsRequest->publicationYear}<br/>
{/if}
{if $materialsRequest->about}
  <strong>Where did you hear about this title?</strong><br/> {$materialsRequest->about}<br/>
{/if}
{if $materialsRequest->comments}
  <strong>Comments:</strong> {$materialsRequest->comments}<br/>
{/if}
{if $materialsRequest->statusLabel}
  <strong>Status:</strong> {$materialsRequest->statusLabel}<br/>
{/if}
{if $materialsRequest->dateCreated}
  <strong>Requested:</strong> {$materialsRequest->dateCreated|date_format}<br/>
{/if}
<hr/>