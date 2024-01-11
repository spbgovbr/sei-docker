CKEDITOR.plugins.add('linkblocoassinatura',
    {
      //requires: [ 'iframedialog' ],
      onLoad: function () {
        CKEDITOR.addCss('.ancora_sei' +
            '{' +
            'background-color: #d5d5d5;' +
            (CKEDITOR.env.gecko ? 'cursor: default;' : '') +
            '}'
        );
        CKEDITOR.dialog.validate.SEIBloco = function () {
          return function (value) {
            value = this && this.getValue ? this.getValue() : value;
            window._id_bloco = value;
            objAjaxBloco.executar();
            return ""!=window._id_bloco_ret
          }
        }
      },

      init: function (editor) {
        editor.addCommand('linkblocoassinaturaDialog', new CKEDITOR.dialogCommand('linkblocoassinaturaDialog'));
        editor.ui.addButton('linkblocoassinatura',
            {
              label: 'Inserir um Link para bloco de assinatura do SEI!',
              command: 'linkblocoassinaturaDialog',
              icon: this.path + 'images/sei.png'
            });

        //var height = 200, width = 750;
        //var linksei=  "http://sei.trf4.jus.br";

        CKEDITOR.dialog.add('linkblocoassinaturaDialog', function (editor) {
          return {
            title: 'Propriedades do Link',
            minWidth: 200,
            minHeight: 70,
            contents:
                [
                  {
                    id: 'general',
                    label: 'Settings',
                    elements:
                        [
                          {
                            type: 'text',
                            id: 'id_bloco',
                            label: 'Bloco de Assinatura',
                            validate: CKEDITOR.dialog.validate.SEIBloco(),
                            required: true,
                            commit: function (data) {
                              data.id = window._id_bloco;
                            }
                          }
                        ]
                  }
                ],
            onOk: function () {
              var dialog = this,
                  data = {},
                  span = editor.document.createElement('span'),
                  link = editor.document.createElement('a');
              this.commitContent(data);
              span.setAttributes({
                contentEditable: "false",
                'data-cke-linkblocoassinatura': 1,
                'style': "text-indent:0px;"
              });
              link.setAttributes({
                'id': 'lnkBlocoSei' + window._id_bloco,
                'class': "ancora_sei",
                'style': "text-indent:0px;"
              });
              link.setHtml(data.id);
              span.append(link);
              editor.insertElement(span);
            }
          };
        });


      }
    });