$ ->
  # tabs
  $('body').on 'click', '.tabs > ul li a[href^=#], [role=tab] a', (e) ->
    $this = $ this
    unless $this.hasClass('disabled')
      
      if $this.parents('[role=tabpanel]').length > 0
        tabs = $this.parents('[role=tabpanel]')
      else
        tabs = $this.parents('.tabs')
        
      tabs.find('> ul li a, [role=tab] a').removeClass('active')
      $this.addClass('active')
      tabs.children('div, [role=tabpanel]').removeClass('active')
      tabs.children($this.attr('href')).addClass('active')
      
    e.preventDefault()
    return false
    
  return
