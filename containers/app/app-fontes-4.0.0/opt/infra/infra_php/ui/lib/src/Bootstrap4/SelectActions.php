<?php

namespace TRF4\UI\Bootstrap4;

use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\Config;

trait SelectActions
{

    protected function buildElements(): void
    {
        $label = $this->_label;
        $select = $this->_select;

        if ($this->withSearchFilter) {
            $select->dataLiveSearch('true');
            $select->dataLiveSearchNormalize('true');
        }

        Bootstrap4::transformLabel($this);

        $label->class('d-block');

        $this->_select->innerHTML = $this->optionsToHtml();

        $this->buildHintIfIsSet();

        if($this->_hintWrapper){
            $select = $this->_hintWrapper;
        }

        if ($this->dependencies) {
            $label->class('d-flex');
            $spinnerPlaceholder = $this->buildSpinnerDropdownId();

            $label->innerHTML = <<<h
                <span class="d-flex">$label->innerHTML</span>
                <span id='$spinnerPlaceholder' style="display:none;">
                    <div class="d-flex text-primary ml-2 badge badge-light align-content-center badge-pill">
                        <span class="mr-2">Carregando...</span>
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </span>
h;
        }

        $this->_wrapper->class('form-group');

    }

    private function buildSpinnerDropdownId()
    {
        return $this->getDefaultElement()->getAttrId() . '-spinner';
    }

    protected function assembleAndPrintElements(): string
    {
        $select = $this->_select;
        $wrapper = $this->_wrapper;

        $id = $select->getAttrId();
        
        if($this->get('multiple')){    
            $select->overrideName($select->get('name')."[]");
            $select->id($id);
        }

        $wrapper->innerHTML($this->_label . $select);

        $wrapperHTML = $wrapper->render();

        $depHtml = $this->buildDependenciesForSelect();

        $html = $wrapperHTML . $depHtml;

        $feedbackContainerHTML = json_encode(Bootstrap4::getFeedbackForInvalidValue($this, Config::getSelectInvalidValueFeedbackFilter()));
        $this->scripts[] = "UI.PHPHelper.select.init('$id', $feedbackContainerHTML);";

        return $html;
    }


    private function buildDependenciesForSelect(): string
    {
        $select = $this;

        if ($this->dependencies === null) {
            return '';
        }

        $spinnerPlaceholder = json_encode($this->buildSpinnerDropdownId());

        $childId = json_encode($select->getAttrId());
        $ret = '';

        $callback = $select->callback;
        $placeholder = $select->getPlaceholder();
        $valueAttr = $callback->valueAttr;
        $innerHTMLAttr = $callback->innerHTMLAttr;

        $url = json_encode($callback->url);
        $params = ['params' => $callback->params];
        $params = str_replace(['"', "'"], '', json_encode($params));
        $dataMap = json_encode($callback->dataMap, JSON_FORCE_OBJECT);

        foreach ($select->dependencies as $dependency) {
            $placeholderIfNull = json_encode($dependency->placeholderIfNull);
            $inputId = json_encode($dependency->inputId);
            $dependencyName = $dependency->name;


            $ret .= <<<html
                    <script>

                        /*comum*/
                        function clearSelect(el) {
                            el.disabled = true;
                            el.innerHTML = '';
                            $("#"+el.id).selectpicker('refresh');
                        }

                        /*comum*/
                        function hideSpinner(spinnerPlaceholder) {
                            spinnerPlaceholder.style = 'display:none;';
                        }

                        function showSpinner(spinnerPlaceholder) {
                            spinnerPlaceholder.style = '';
                        }

                        //todo formatar o resultado (nao posso garantir que vai ser sempre innerHTML, value e data

                        (function(){
                            

                            var $dependencyName = document.getElementById($inputId)

                            //todo quem gera isso é o pai né... assim vai ter duplicatas no caso de mais de um elemento dependente
                            function getSelectCountriesData(el) {
                                var selectedOption = el.selectedOptions[0];
                                var data = {
                                    innerHTML: selectedOption.innerHTML,
                                    value: selectedOption.value,
                                    data: selectedOption.dataset
                                };
                                return data;
                            }

                            var childId = $childId;
                            var childEl = document.getElementById(childId);

                            //tratamento do evento por parte do states
                            $($dependencyName).change(function (e) {
                                var select = this;
                                var data = getSelectCountriesData(select);
                                var param = data.innerHTML;
                                var placeholder = "Select a state";
                                var spinnerPlaceholderId = $spinnerPlaceholder;
                                var spinnerPlaceholder = document.getElementById(spinnerPlaceholderId);
                                
                                clearSelect(childEl);

                                if (select.value){

                                    showSpinner(spinnerPlaceholder);

                                    axios.$callback->method($url, $params)
                                        .then(function(data){
                                            childEl.disabled = false;
                                            addOptionsToSelect(placeholder, childEl, data.data);
                                        })
                                        .catch(function(error){
                                             console.error(error); //todo exibir algo personalizado
                                        })
                                        .finally(function(){
                                            hideSpinner(spinnerPlaceholder);
                                        });
                                } else {
                                    setChildSelectDisabled($placeholderIfNull, childEl);                                    
                                }
                            });
                            
                            function setChildSelectDisabled(placeholder, select){
                                childEl.disabled = true;
                                addOptionsToSelect(placeholder, select, []);                               
                            }
                            

                            function addOptionsToSelect(placeholder, select, setOptions) {
                                var optionsHTML = '<option>' + placeholder + '</option>';

                                setOptions.forEach(function(v, k){
                                    var value = $valueAttr;
                                    var innerHTML = $innerHTMLAttr;
                                    var dataset = [];
                                    Object.getOwnPropertyNames($dataMap).forEach(function(returnAttr, optionAttr){
                                        var returnValue = o[returnAttr];
                                        dataset.push(optionAttr + '="'+returnValue+'"');
                                    });

                                    var optionAttrsString = dataset.join(' ');

                                    optionsHTML += '<option value="'+value+'" ' + optionAttrsString +'>'+innerHTML+'</option>';
                                });
                                
                                select.innerHTML = optionsHTML;
                                $("#"+select.id).selectpicker('refresh');

                            }
                            
                            $($dependencyName).change();
                        })();

                </script>
html;
        }

        return $ret;
    }
}
