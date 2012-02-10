<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );


// Customer Reviews
	if($this->allowRating || $this->showReview) {
		$maxrating = VmConfig::get('vm_maximum_rating_scale',5);
		$ratingsShow = VmConfig::get('vm_num_ratings_show',3); // TODO add  vm_num_ratings_show in vmConfig
		//$starsPath = JURI::root().VmConfig::get('assets_general_path').'images/stars/';
		$stars = array();
		$showall = JRequest::getBool('showall', false);
		for ($num=0 ; $num <= $maxrating; $num++  ) {
			$title = (JText::_("COM_VIRTUEMART_RATING_TITLE") . $num . '/' . $maxrating) ;
			$stars[] = '<span class="vmicon vm2-stars'.$num.'" title="'.$title.'"></span>';
		} ?>

	<div class="customer-reviews">
		<form method="post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$this->product->virtuemart_product_id.'&virtuemart_category_id='.$this->product->virtuemart_category_id) ; ?>" name="reviewForm" id="reviewform">
	<?php
	}

	if($this->showReview) {

		?>
		<h4><?php echo JText::_('COM_VIRTUEMART_REVIEWS') ?></h4>

		<div class="list-reviews">
			<?php
			$i=0;
			$review_editable=true;
			$reviews_published=0;
			if ($this->rating_reviews) {
				foreach($this->rating_reviews as $review ) {
					if ($i % 2 == 0) {
						$color = 'normal';
					} else {
						$color = 'highlight';
					}

					/* Check if user already commented */
	 				// if ($review->virtuemart_userid == $this->user->id ) {
					if ($review->created_by == $this->user->id && !$review->review_editable) {
	 					$review_editable = false;
	 				}
					?>

					<?php // Loop through all reviews
					if (!empty($this->rating_reviews) && $review->published) {
					    $reviews_published++;
					    ?>
					<div class="<?php echo $color ?>">
						<span class="date"><?php echo JHTML::date($review->created_on, JText::_('DATE_FORMAT_LC')); ?></span>
						<span class="vote"><?php echo JText::_('COM_VIRTUEMART_RATING')." ".$review->review_rates; ?></span>
						<blockquote><?php echo $review->comment; ?></blockquote>
						<span class="bold"><?php echo $review->customer ?></span>
					</div>
					<?php
					}
					$i++ ;
					if ( $i == $ratingsShow && !$showall) {
						/* Show all reviews ? */
						if ( $reviews_published >= $ratingsShow ) {
							$attribute = array('class'=>'details', 'title'=>JText::_('COM_VIRTUEMART_MORE_REVIEWS'));
							echo JHTML::link($this->more_reviews, JText::_('COM_VIRTUEMART_MORE_REVIEWS'),$attribute);
						}
						break;
					}
				}

			} else {
				// "There are no reviews for this product" ?>
				<span class="step"><?php echo JText::_('COM_VIRTUEMART_NO_REVIEWS') ?></span>
			<?php
			}  ?>
		<div class="clear"></div>
		</div>

		<?php // Writing A Review
		if($this->allowReview ) { ?>
		<div class="write-reviews">

			<?php // Show Review Length While Your Are Writing
			$reviewJavascript = "
			function check_reviewform() {
				var form = document.getElementById('reviewform');

				var ausgewaehlt = false;

				for (var i=0; i<form.vote.length; i++) {
					if (form.vote[i].checked) {
						ausgewaehlt = true;
					}
				}
					if (!ausgewaehlt)  {
						alert('".JText::_('COM_VIRTUEMART_REVIEW_ERR_RATE',false)."');
						return false;
					}
					else if (form.comment.value.length < ". VmConfig::get('reviews_minimum_comment_length', 100).") {
						alert('". addslashes( JText::sprintf('COM_VIRTUEMART_REVIEW_ERR_COMMENT1_JS', VmConfig::get('reviews_minimum_comment_length', 100)) )."');
						return false;
					}
					else if (form.comment.value.length > ". VmConfig::get('reviews_maximum_comment_length', 2000).") {
						alert('". addslashes( JText::sprintf('COM_VIRTUEMART_REVIEW_ERR_COMMENT2_JS', VmConfig::get('reviews_maximum_comment_length', 2000)) )."');
						return false;
					}
					else {
						return true;
					}
				}

				function refresh_counter() {
					var form = document.getElementById('reviewform');
					form.counter.value= form.comment.value.length;
				}";
			$document = &JFactory::getDocument();
			$document->addScriptDeclaration($reviewJavascript);

			if($this->showRating) {
				if($this->allowRating && $review_editable) { ?>
					<h4><?php echo JText::_('COM_VIRTUEMART_WRITE_REVIEW')  ?><span><?php echo JText::_('COM_VIRTUEMART_WRITE_FIRST_REVIEW') ?></span></h4>
					<span class="step"><?php echo JText::_('COM_VIRTUEMART_RATING_FIRST_RATE') ?></span>
					<ul class="rating">

					<?php // Print The Rating Stars + Checkboxes
					for ($num=0 ; $num<=$maxrating;  $num++ ) { ?>
						<li id="<?php echo $num ?>_stars">
							<label for="vote<?php echo $num ?>"><?php echo $stars[ $num ]; ?></label>
							<?php
							if ($num == 5) {
								$selected = ' checked="checked"';
							} else {
								$selected = '';
							} ?>
							<input<?php echo $selected ?> id="vote<?php echo $num ?>" type="radio" value="<?php echo $num ?>" name="vote">
						</li>
					<?php } ?>
					</ul>

					<?php

				}
			}
			if($review_editable ) { ?>
				<span class="step"><?php echo JText::sprintf('COM_VIRTUEMART_REVIEW_COMMENT', VmConfig::get('reviews_minimum_comment_length', 100), VmConfig::get('reviews_maximum_comment_length', 2000)); ?></span>
				<br />
				<textarea class="virtuemart" title="<?php echo JText::_('COM_VIRTUEMART_WRITE_REVIEW') ?>" class="inputbox" id="comment" onblur="refresh_counter();" onfocus="refresh_counter();" onkeyup="refresh_counter();" name="comment" rows="5" cols="60"><?php if(!empty($this->review->comment))echo $this->review->comment; ?></textarea>
				<br />
				<span><?php echo JText::_('COM_VIRTUEMART_REVIEW_COUNT') ?>
				<input type="text" value="0" size="4" class="vm-default" name="counter" maxlength="4" readonly="readonly" />
				</span>
				<br /><br />
				<input class="highlight-button" type="submit" onclick="return( check_reviewform());" name="submit_review" title="<?php echo JText::_('COM_VIRTUEMART_REVIEW_SUBMIT')  ?>" value="<?php echo JText::_('COM_VIRTUEMART_REVIEW_SUBMIT')  ?>" />
			</div>
			<?php
			} else {
				echo '<strong>'.JText::_('COM_VIRTUEMART_DEAR').$this->user->name.',</strong><br />' ;
				echo JText::_('COM_VIRTUEMART_REVIEW_ALREADYDONE');
			}
		}
	}

	if($this->allowRating || $this->showReview) {
	?>
			<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>" />
			<input type="hidden" name="option" value="com_virtuemart" />
			<input type="hidden" name="virtuemart_category_id" value="<?php echo JRequest::getInt('virtuemart_category_id'); ?>" />
			<input type="hidden" name="virtuemart_rating_review_id" value="0" />
			<input type="hidden" name="task" value="review" />
		</form>
	</div>
	<?php
	}