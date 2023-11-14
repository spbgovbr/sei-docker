CKEDITOR.dialog.add( 'linkblocoassinatura', function( editor )
{
	return {
		title : 'Propriedades do Link',
		minWidth : 200,
		minHeight : 70,
		contents :
		[
			{
				id : 'general',
				label : 'Settings',
				elements :
				[
					{
						type : 'text',
						id : 'id_bloco',
						label : 'Bloco de Assinatura',
						validate : CKEDITOR.dialog.validate.SEIBloco(),
						required : true,
						setup: function(widget){
							this.setValue(widget.data.id);
						},
						commit : function( widget )
						{
							widget.setData('id','lnkBlocoSei'+window._id_bloco);
							widget.setData('text',widget.data.id);
						}
					}
				]
			}
		]		
  };
});