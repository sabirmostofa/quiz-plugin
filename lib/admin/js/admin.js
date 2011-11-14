jQuery.fn.exists = function () {
    return jQuery(this).length > 0;
}
jQuery(document).ready(function(){
    
    if(jQuery("#quizform").exists()) {
        jQuery("#quizform").validate();
    }
});

jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  })
}
});

function quiz_insert_tag(target, tag, isTinymce) {
    if(isTinymce)
        tinyMCE.activeEditor.selection.setContent(tag);
    else
        jQuery(target).insertAtCaret(tag);
    
    return false;
}