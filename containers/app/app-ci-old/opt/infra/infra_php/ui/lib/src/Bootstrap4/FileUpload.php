<?php


namespace TRF4\UI\Bootstrap4;

use TRF4\UI\Element\GenericElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class FileUpload extends \TRF4\UI\Component\FileUpload
{

    /** @var GenericElement */
    public $_wrapper;
    public $_wrapperInput;

    public function __construct(string $labelInnerHtml, ?string $name = null) {
        parent::__construct($labelInnerHtml, $name);
        $this->_wrapper = UI::el('div')->class('form-group');
        $this->_wrapperInput = UI::el('div')->class('file-loading');
    }

    public function render(): string
    {

        $required = ($this->get("required"))? 'true' : 'false';

        // altera o nome para incluir [] em caso de multiple
        if($this->get('multiple')){
            $id = $this->getAttrId();
            $this->_input->name("[]")->id($id);
            $input = UI::el('input')
                    ->type('file')
                    ->multiple()
                    ->name($this->_input->get('name'))
                    ->id($this->_input->getAttrId());
        } else {
            $input = UI::el('input')->type('file')->name($this->_input->get('name'))->id($this->_input->getAttrId());
        }

        $scripts = "";
        $inputId = $this->getAttrId();

        $this->buildHintIfIsSet(null, "no-label");

        if($this->_hintWrapper){
            $this->_label = UI::el('label')->class('w-100')->innerHTML($this->getHint());
        }

        if($this->getHint()) $scripts .= "$('#$inputId-hint').popover();";

        $inputHTML = $input->render();

        $maxFiles = ( $this->getMaxFiles() ) ? $this->getMaxFiles() : 1;

        $allowedFileExtensions = "";
        if( $this->allowedFileExtensions ){
            $formats = "'" . implode ( "', '", $this->getAllowedFileExtensions() ) . "'";
            $allowedFileExtensions = ($this->getAllowedFileExtensions() )? "allowedFileExtensions:[".$formats."]," : "";
        }

        # monta bloco JS com os parâmetros de upload
        $showUpload = ( strlen( $this->getUrlAjax() ) ) ? "true" : "false";
        $uploadUrl  = ( strlen( $this->getUrlAjax() ) ) ? "uploadUrl: '".$this->getUrlAjax()."'," : "";
        $showUpload = ( strlen( $this->getUrlAjax() ) ) ? "true" : "false";
        $async      = ( $this->isAsync() ) ? "true" : "false";

        $overrideFile = ( $this->isOverrideFile() )? 'true' : 'false';

        $deleteUrl = ( strlen($this->getDeleteUrl()) )? "deleteUrl: '".$this->getDeleteUrl()."'," : "";

        $initialPreview = (isset($this->initFiles['url']))? "initialPreview: ".json_encode($this->initFiles['url'])."," : "";
        $initialPreviewConfig = (isset($this->initFiles['config']))? "initialPreviewConfig:".json_encode($this->initFiles['config']) : "";

        # Adiciona Hidden indicando se no back-end os arquivos anteriores serão subescritos pelos novos arquivos
        $hiddenInput = "";
        if($this->isOverrideFile()){
            $hiddenInput = UI::el('input')
                ->type('hidden')
                ->name($this->getAttrId()."_Override")
                ->value("true")
                ->render();
        }

        // required
        $showRemove = ( $this->isShowRemove() )? 'true' : 'false';
        $showCancel = ( $this->isShowCancel() )? 'true' : 'false';

        $js = <<<html
            <script type="text/javascript">  
                
                csrfToken = $('#csrf-token').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }); 

                (function(){
                    $('#$inputId').fileinput({
                        theme: 'fas',
                        language: "pt-BR",
                        required: $required,
                        validateInitialCount: true,
                        showRemove: $showRemove,
                        showCancel: $showCancel,
                        $uploadUrl
                        uploadAsync: $async,
                        $allowedFileExtensions
                        overwriteInitial: $overrideFile,
                        maxFileCount: $maxFiles,
                        showUpload: $showUpload,
                        initialPreviewAsData: false,
                        uploadExtraData: {_token: csrfToken },
                        $deleteUrl
                        $initialPreview
                        $initialPreviewConfig
                    });
                })();
                $scripts
            </script>
html;

        Bootstrap4::transformLabel($this);

        $label = "";
        if($this->hasLabel()){
            $label = $this->_label->render();
        }

        $this->_wrapperInput->innerHTML($this->_input);

        $this->_wrapper->innerHTML(
            $label .
            $hiddenInput .
            $this->_wrapperInput->render() .
            $js
        );
        return $this->_wrapper->render();
    }

    protected function buildElements(): void {
        $this->_input->class('form-control form-control-sm');

        Bootstrap4::transformLabel($this);
    }

    protected function assembleAndPrintElements(): string {

        $this->_wrapper->innerHTML(
            $this->_label->render() .
            $this->_input->render() .
            Bootstrap4::getFeedbackForInvalidValue($this)
        );

        return $this->_wrapper->render();
    }
}
