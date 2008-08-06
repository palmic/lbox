hs.graphicsDir = '/js/highslide/3.3.22/graphics/';
hs.outlineType = 'drop-shadow';
hs.restoreTitle = 'Klikněte pro zavření obrázku, posunujte táhnutím.';
hs.loadingText = 'Načítám...';
hs.loadingTitle = 'Kliknutím zrušíte načítání';
hs.focusTitle = 'Kliknutím přesunete do popředí.';
hs.fullExpandTitle = 'Expanduji na aktuální velikost.';
hs.fullExpandPosition = 'bottom right';
window.addEvent('domready', function() {
	$$('a.highslide').each(function(item, index) {
		item.addEvent('mouseover', function() {
			return hs.expand(this);
		})
	});
});