function repositionMessage(){
		lastTop0 = lastTop1 = 0;
		row = 0;
		$(".messages > li").each(function() {
			$(this).attr("rel", "message_" + messageID++);
			$(this).addClass("column" + row).css('position','absolute');
			if(row==0){
				$(this).removeClass("column" + 1);
				$(this).css("top",lastTop0+'px');
				lastTop0 = lastTop0 + $(this).height()+45;
				if(lastTop0 >= (lastTop1-50)) row = 1;
			}
			else {
				$(this).removeClass("column" + 0);
				$(this).css("top",lastTop1+'px');
				lastTop1 = lastTop1 + $(this).height()+45;
				if(lastTop1 >= (lastTop0-50)) row = 0;
			}
		});
		if(lastTop1 > lastTop0)
			$(".messages").height(lastTop1+'px');
		else
			$(".messages").height(lastTop0+'px');
	}
	
	
	
	$(function() {
	
		messages = {};
		messageID = 0;
		
		var showingBtn = false;
		var showingAdd = false;
		var hideTimer = null;
		var plusBtnMouseMoved = false;
		
		function elasticTextarea(){
			console.log($('textarea'));
			$('textarea').elastic();
			$('textarea').keypress(function(e){
				if(e.keyCode ==13){
					if($(this).val() != ''){
						submitComment($(this));
					}
					return false;
				}
			});
		}
		function submitComment( _this ){
			console.log('submitComment');
			var blockNewComm = _this.parent().parent();
			var containerComm = _this.parent().parent().parent();
			var commentTemp = $('#CommentTemplateList').find('.templateComment');
			var cloneTemp = commentTemp.clone();
			cloneTemp.removeAttr('class');
			//$.post();
			cloneTemp.find('.commentAvatar').attr('src','https://fbcdn-profile-a.akamaihd.net/hprofile-ak-snc4/211259_522204617_1386981_q.jpg');
			cloneTemp.find('.commentStatus').html('<a href="">Randdy Thea</a>: '+_this.val());
			cloneTemp.find('.commentTime').html('2 weeks ago from San Francisco');
			_this.val('');
			blockNewComm.before(cloneTemp);
			repositionMessage();
		}
		elasticTextarea();
		
		repositionMessage();
		$(".messages > li .photoContainer img").load(function(){ // defend if photo to late loaded, reposition again
			repositionMessage();
		});
		
		$(".messagesContainer, .plusBtn").mousemove(function(e) {
			
			if (showingAdd) return true;
			/*
			if ($(this)[0].className == "messagesContainer" && plusBtnMouseMoved) {
			//	plusBtnMouseMoved = false;
			//	return true;
			}
			*/
			var isPlusBtn = $(this)[0].className == "plusBtn";
			//if (isPlusBtn) plusBtnMouseMoved = true;
			
			var plusBtn = isPlusBtn ? $(this) : $(this).find(".plusBtn");
			
			//var offsetX = e.offsetX + (isPlusBtn ? plusBtn.position().left : 0);
			var offsetX = e.pageX - $(this).offset().left;//(isPlusBtn ? $(this).offset().left : $(this).offset().left);
			//console.log(($(this)[0].className) + "    " + offsetX);
			
			var containerWidth = $(this).width();
			var separatorWidth = plusBtn.width();
			
			if (isPlusBtn || (offsetX >= (containerWidth - separatorWidth) / 2 && offsetX <= (containerWidth + separatorWidth) / 2)) {
				var messagesContainer = !isPlusBtn ? $(this) : $(this).parent();
				plusBtn.css("top", e.pageY - messagesContainer.position().top - plusBtn.height() / 2 + "px");
				plusBtn.show();
			} else if (!showingAdd) {
				//isPlusBtn = false;
				plusBtn.hide();
				//console.log ("Hide");
			}
			
		
		});
		
		$("body").click(function(e) {
			var $target = $(e.target);
			if (!showingAdd || e.target.className == "plusBtn" || e.target.className == "addBox" || $target.parents(".addBox").size() > 0) return true;
			hideAddBox();
		});
		
		$(".plusBtn").click(function() {
			if (!showingAdd) {
				showAddBox();
			} else {
				hideAddBox();
			}
		});
		
		$(".likeButton").click(function() {
			var span = $(this).find("span");
			var original = parseInt(span.text());
			var result = original + 1; // you should perform AJAX here
			span.text(result);
			return false;
		});
		
		$("#addBox_button a").click(function() {
			var container_id = "addBox_" + $(this).attr("rel");
			var container = $("#" + container_id);
			if (container.size() == 0) return false;
			$(this).parent().parent().find("> div").hide();
			container.show();
			container.find("textarea, input").eq(0).focus();
			adjustAddBox();
		});
		
		$("#addBox_status form").submit(function() {
			var avatar = "https://fbcdn-profile-a.akamaihd.net/hprofile-ak-snc4/211259_522204617_1386981_q.jpg";
			var status = $(this).find("textarea").val();
			var location = "Test Location";
			var likeCount = 1;
			var _y = $(".addBox").offset().top;
			var appendToMessage = findNearestMessage(_y);
			addMessage(appendToMessage, avatar, status, location, likeCount,'even');
			hideAddBox();
			return false;
		});
		
		$(".icon").click(function() {
			var li = $(this).parent(".messages > li");
			if (li) {
				removeMessage(li.attr("rel"));
			}
			return false;
		});
		
		$(window).resize(function () {
			if (showingAdd) {
				var plusBtn = $(".plusBtn");
				var addBox = $(".addBox");
				addBox.css({
					left: plusBtn.offset().left,
					top: plusBtn.offset().top
				});
			}
		});
		
		function showAddBox() {
			showingAdd = true;
			var plusBtn = $(".plusBtn");
			plusBtn.show();
			
			var addBox = $(".addBox");
			/*
			addBox.css({
				left: plusBtn.offset().left,
				top: plusBtn.offset().top,
				"margin-left": -1 * (addBox.width() + plusBtn.width())
			});
			*/
			addBox.find("> div").hide();
			addBox.find("#addBox_button").show();
			
			adjustAddBox();
			
			addBox.show();
		}
		
		function hideAddBox() {
			showingAdd = false;
			$(".plusBtn").hide();
			var addBox = $(".addBox");
			addBox.hide();
			addBox.find("input[type=text]").val("");
			addBox.find("textarea").val("");
		}
		
		function adjustAddBox() {
			$(".addBox > div").each(function() {
				var display = $(this).css("display");
				if (display == "none") return true;
				
				var width = $(this).width();
				var height = $(this).height();
				
				var plusBtn = $(".plusBtn");
				var addBox = $(this).parent();
				addBox.css({
					left: plusBtn.offset().left,
					top: plusBtn.offset().top,
					"margin-left": -1 * (addBox.width() + $(".plusBtn").width())
				});
				
				if (display == "block") return false;
			});
		}
		
		function findNearestMessage(y) {
			var message = null; // default
			var differences = [];
			$(".messages > li:not(#messageTemplate)").each(function() {
				var _y1 = $(this).offset().top;
				var _y2 = _y1 + $(this).height();
				if (y >= _y1 && y <= _y2) {
					message = $(this);
					//return false;
				} else {
					differences.push(_y2 - y);
				}
			});
			
			if (message == null) {
				var _message = null;
				var _smallest = 1000;
				for (var d in differences) {
					if (differences[d] < _smallest) {
						_smallest = differences[d];
						_message = d;
					}
				}
				if (_message != null)
					message = $(".messages > li:not(#messageTemplate)").eq(_message);
			}
			
			if (message == null) {
				message = $(".messages > li:not(#messageTemplate):last-child");
			}
			
			return message;
		}
		
		function addMessage(appendTo, avatar, status, location, likeCount,type) {
			// Clone the existing template element
			var clone = $("#messageTemplate").clone(true);
			
			//if (appendTo.hasClass("even"))
			clone.addClass(type);
			
			// Set the display state of the cloned element
			clone.css("display", "block");
			clone.attr("rel", "message_" + messageID++);
			
			// Add a pale yellow background
			/*
			var newMessage = $("<div />");
			newMessage.addClass("newMessage");
			clone.prepend(newMessage);
			*/
			
			// Get the different parts for modifications
			var ele_avatar = clone.find("*[rel=avatar]");
			var ele_status = clone.find("*[rel=status]");
			var ele_location = clone.find("*[rel=location]");
			var ele_likeCount = clone.find("*[rel=likeCount]");
			
			// These data will be retrieved from server side
			ele_avatar.attr("src", avatar);
			ele_status.html(status);
			ele_location.html(location);
			ele_likeCount.text(likeCount);
			
			// Add the final message element to the --beginning-- of the container
			//$(".messages").prepend(clone);
			appendTo.after(clone);
			
			//var original_height = clone.height();
			//clone.css("height", 0);
			//clone.animate({height:original_height}, 2000);
			clone.hide();
			clone.fadeIn("fast");
			clone.removeAttr("id");
			repositionMessage();
			elasticTextarea();
			setTimeout(function() {
				clone.find(".newMessage").fadeOut(function(){$(this).remove();});
			}, 3000);
		}
		
		function removeMessage(rel) {
			
			var ele = $(".messages li[rel=" + rel + "]");
			
			$(".icon").fadeOut();
			
			ele.animate({
				opacity:0
			}, 300);
			
			setTimeout(function() {
				$(".icon").fadeIn();
				ele.remove();
				repositionMessage();
			}, 1000);
		}
		
		// for debugging
		window.addMessage = addMessage;
		window.removeMessage = removeMessage;
	
	});
