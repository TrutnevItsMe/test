this.BX=this.BX||{},this.BX.YandexMarket=this.BX.YandexMarket||{},this.BX.YandexMarket.UI=this.BX.YandexMarket.UI||{},function(t){"use strict";const e=window.BX;class s{constructor(t={}){this.onEditorInit=(t,e)=>{e.methods[this.options.name]=this.build,this.handleEditorInit(!1)},this.build=(t,e,s)=>{const i=this.options.name+"_";if(0!==t.indexOf(i))return null;const n=t.substr(i.length);if(!this.options.map.hasOwnProperty(n))return null;return this.options.map[n].create(e,s)},this.options=Object.assign({},this.constructor.defaults,t)}register(){this.isEditorInitialized()?this.registerEditorMethod():this.handleEditorInit(!0)}isEditorInitialized(){var t,s;return null==(t=e.UI)||null==(s=t.EntityEditorControlFactory)?void 0:s.initialized}registerEditorMethod(){e.UI.EntityEditorControlFactory.registerFactoryMethod(this.options.name,this.build)}handleEditorInit(t){e[t?"addCustomEvent":"removeCustomEvent"](window,"BX.UI.EntityEditorControlFactory:onInitialize",this.onEditorInit)}}function i(t,e="div"){const s=document.createElement(e);return s.innerHTML=t,s.firstElementChild}function n(t){return t.split("_").map(((t,e)=>t.toLowerCase())).join("-")}function o(t,e){return r(e,a(t))}function a(t){const e=l(t),s={};for(const[t,i]of Object.entries(e))s[t]=i.value;return s}function r(t,e){const s=l(t);let i=!1;for(const[t,n]of Object.entries(e)){if(null==s[t])continue;const e=s[t];e.value!==n&&(i=!0,e.value=n)}return i}function l(t){const e=t.querySelectorAll("input, select, textarea"),s={};for(const t of e)t.name&&(s[t.name]=t);return s}function d(t,e){let s=t;if(null==e)return s;for(const[t,i]of Object.entries(e))s=s.replace("#"+t+"#",i);return s}s.defaults={name:"yamarket",map:{}};class u extends BX.UI.EntityEditorField{constructor(){super(),this._hasLayout=!1,this.options=null!=this.constructor.defaults?Object.assign({},this.constructor.defaults):{}}getMessage(t,e=null){let s=this.constructor.messages[t]||super.getMessage(t);return null!=e&&(s=d(s,e)),s}clearLayout(){this._hasLayout&&(this.forget(),this._wrapper.innerHTML="",this._hasLayout=!1)}layout(t){if(!this._hasLayout){if(this.ensureWrapperCreated({}),this.adjustWrapper(),!this.isNeedToDisplay())return this.registerLayout(t),void(this._hasLayout=!0);if(this.forget(),this._wrapper.innerHTML="",this.isDragEnabled()&&this._wrapper.appendChild(this.createDragButton()),this.useTitle()){const t=this.getTitle();this._wrapper.appendChild(this.createTitleNode(t))}this.render(this.getValue()),this.isContextMenuEnabled()&&this._wrapper.appendChild(this.createContextMenuButton()),this.isDragEnabled()&&this.initializeDragDropAbilities(),this.registerLayout(t),this._hasLayout=!0}}forget(){}render(t){}useTitle(){return!1}fewElements(t){const e=this.getElementSelector(t);let s;return s=0===e.indexOf("#")?document.querySelectorAll(e):this.el.querySelectorAll(e),s}getElement(t){const e=this.getElementSelector(t);let s;return s=0===e.indexOf("#")?document.querySelector(e):this.el.querySelector(e),s}getElementSelector(t){return this.options[t+"Element"]}}class h{constructor(t){this.onStatusClick=t=>{this.openDialog(),t.preventDefault()},this.options=Object.assign({},this.constructor.defaults,t),this.el=null}bind(){this.handleStatusClick(!0)}handleStatusClick(t){this.getElement("status")[t?"addEventListener":"removeEventListener"]("click",this.onStatusClick)}setup(t){this.el=t,this.bind()}build(){return`<div class="yamarket-item-summary">\n\t\t\t${this.buildStatus()}\n\t\t\t<div class="yamarket-item-summary__modal" hidden>\n\t\t\t\t${this.buildForm()}\n\t\t\t</div>\n\t\t</div>`}buildStatus(){const t=this.optionValue(),e=this.getStatus(t);return`<a class="yamarket-item-summary__status" href="#" data-status="${e}">${this.getMessage("SUMMARY_"+e)}</a>`}reflowStatus(){const t=this.formValue(),e=this.getStatus(t),s=this.getElement("status");s.setAttribute("data-status",e),s.textContent=this.getMessage("SUMMARY_"+e)}getStatus(t){throw new Error("not implemented")}buildForm(t=!1){throw new Error("not implemented")}reflowForm(){this.el.querySelector(".yamarket-item-summary__modal").innerHTML=this.buildForm(!0)}optionValue(){throw new Error("not implemented")}formValue(){throw new Error("not implemented")}openDialog(){const t=this.getElement("modal").firstElementChild,e=BX.UI.Dialogs.MessageBox.create({title:this.getMessage("MODAL_TITLE")});e.setMessage(t.cloneNode(!0)),e.setButtons([new BX.UI.SaveButton({events:{click:this.onSaveClick.bind(this,e)}}),new BX.UI.CancelButton({events:{click:this.onCancelClick.bind(this,e)}})]),e.show()}onSaveClick(t){o(t.popupWindow.contentContainer,this.el)&&this.options.onChange&&this.options.onChange(),this.reflowStatus(),t.close()}onCancelClick(t){t.close()}getMessage(t,e=null){let s=this.options.messages[t]||t;return null!=e&&(s=d(s,e)),s}fewElements(t){const e=this.getElementSelector(t);return this.el.querySelectorAll(e)}getElement(t){const e=this.getElementSelector(t);return this.el.querySelector(e)}getElementSelector(t){return this.options[t+"Element"]}}h.STATUS_READY="READY",h.STATUS_WAIT="WAIT",h.STATUS_EMPTY="EMPTY",h.defaults={modalElement:".yamarket-item-summary__modal",statusElement:".yamarket-item-summary__status",inputElement:"input",name:null,messages:{},onChange:null};class c extends h{constructor(...t){super(...t),this.onCopyClick=t=>{this.copyInternal(),t.preventDefault()}}bind(){super.bind(),this.handleCopyClick(!0)}handleCopyClick(t){const e=this.getElement("copy");null!=e&&e[t?"addEventListener":"removeEventListener"]("click",this.onCopyClick)}updateTotal(t){this.options.total=t,this.reflowStatus(),this.reflowForm()}build(){const t=this.optionValue("internal");return`<div class="yamarket-item-summary">\n\t\t\t${this.buildStatus()}\n\t\t\t${t.length>0?this.buildCopyIcon():""}\n\t\t\t<div class="yamarket-item-summary__modal" hidden>\n\t\t\t\t${this.buildForm()}\n\t\t\t</div>\n\t\t</div>`}buildCopyIcon(){return`<button class="yamarket-item-summary__copy" type="button" title="${this.getMessage("COPY")}">\n\t\t\t${this.getMessage("COPY")}\n\t\t</button>`}buildForm(t=!1){const e=parseInt(this.options.total)||0,s=new Array(e).fill(null),i=t?this.formValue():this.optionValue();return`<div class="ui-form">\n\t\t\t${s.map(((t,e)=>{const s=i[e]||"";return`<div class="ui-form-row-inline">\n\t\t\t\t\t<div class="ui-form-row-inline-col">\n\t\t\t\t\t\t<div class="ui-form-label">\n\t\t\t\t\t\t\t<div class="ui-ctl-label-text">&numero;${e+1}</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class="ui-form-content">\n\t\t\t\t\t\t<div class="ui-ctl ui-ctl-sm ui-ctl-textbox ui-ctl-w100">\n\t\t\t\t\t\t\t<input class="ui-ctl-element" type="text" name="${this.options.name}[${e}]" value="${BX.util.htmlspecialchars(s)}" />\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>`})).join("")}\n\t\t</div>`}getStatus(t){let e;return e=t.length>=this.options.total?h.STATUS_READY:t.length>0||this.options.required?h.STATUS_WAIT:h.STATUS_EMPTY,e}copyInternal(t=this.el){r(t,this.makeInternalValues())&&(this.options.onChange&&this.options.onChange(),this.reflowStatus())}makeInternalValues(){const t=this.optionValue("internal"),e={};for(let s=0;s<t.length;++s)e[`${this.options.name}[${s}]`]=t[s];return e}formValue(){const t=[];for(const e of this.fewElements("input")){const s=e.value.trim();""!==s&&t.push(s)}return t}optionValue(t=null){return(null!=t?this.options[t+"Instances"]:this.options.instances).map((t=>t.CIS)).filter((t=>null!=t&&t.length>0))}getMessage(t,e=null){const s="ITEM_CIS_"+t,i=this.options.messages[s];return null!=i?d(i,e):super.getMessage(t,e)}}c.defaults=Object.assign({},h.defaults,{copyElement:null,total:0,required:!1,instances:[],internalInstances:[]});class m extends h{updateTotal(t){this.options.total=t,this.reflowStatus(),this.reflowForm()}buildForm(t=!1){const e=parseInt(this.options.total)||0,s=t?this.formValue():{};return`<div class="ui-form">\n\t\t\t${this.buildFormCodes(e,s)}\n\t\t\t${this.buildFormAdditional(e,s)}\n\t\t</div>`}buildFormCodes(t,e){const s=t>1;return new Array(t).fill(null).map(((t,i)=>{var n;const o=(null==e||null==(n=e.ITEM)?void 0:n[i])||{};let a="",r=`<div class="ui-form-row">\n\t\t\t\t<div class="ui-form-label">\n\t\t\t\t\t<div class="ui-ctl-label-text">${this.getMessage("CODE")}</div>\n\t\t\t\t</div>\n\t\t\t\t<div class="ui-form-content">\n\t\t\t\t\t<div class="ui-ctl ui-ctl-textbox ui-ctl-w100">\n\t\t\t\t\t\t<input class="ui-ctl-element" type="text" name="${this.options.name}[ITEM][${i}][CODE]" value="${BX.util.htmlspecialchars(o.CODE||"")}" />\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t<div class="ui-form-row">\n\t\t\t\t<div class="ui-form-label">\n\t\t\t\t\t<div class="ui-ctl-label-text">${this.getMessage("ACTIVATE_TILL")}</div>\n\t\t\t\t</div>\n\t\t\t\t<div class="ui-form-content">\n\t\t\t\t\t<div class="ui-ctl ui-ctl-after-icon ui-ctl-datetime ui-ctl-w100">\n\t\t\t\t\t\t<div class="ui-ctl-after ui-ctl-icon-calendar"></div>\n\t\t\t\t\t\t<input class="ui-ctl-element" type="text" name="${this.options.name}[ITEM][${i}][ACTIVATE_TILL]" value="${BX.util.htmlspecialchars(o.ACTIVATE_TILL||"")}" onclick="BX.calendar({node: this, field: this, bTime: false, bHideTime: true})" />\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t</div>`;return s&&(a=`<div class="yamarket-form-group-title">${this.getMessage("GROUP",{NUMBER:i+1})}</div>`),a+r})).join("")}buildFormAdditional(t,e){const s=t>1;let i="",n=`<div class="ui-form-row">\n\t\t\t<div class="ui-form-label">\n\t\t\t\t<div class="ui-ctl-label-text">${this.getMessage("SLIP")}</div>\n\t\t\t</div>\n\t\t\t<div class="ui-form-content">\n\t\t\t\t<div class="ui-ctl ui-ctl-textbox ui-ctl-w100">\n\t\t\t\t\t<textarea class="ui-entity-editor-field-textarea" name="${this.options.name}[SLIP]" rows="5" required>${BX.util.htmlspecialchars(e.SLIP||"")}</textarea>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t</div>`;return s&&(i=`<div class="yamarket-form-group-title">${this.getMessage("ADDITIONAL")}</div>`),i+n}getStatus(t){const e=this.filterFilledItems(t.ITEM).length>=this.options.total,s=null!=t.SLIP&&""!==t.SLIP.trim();return e&&s?h.STATUS_READY:h.STATUS_WAIT}filterFilledItems(t){const e=[];for(const s of t)null!=s.CODE&&""!==s.CODE.trim()&&null!=s.ACTIVATE_TILL&&""!==s.ACTIVATE_TILL.trim()&&e.push(s);return e}optionValue(){return{ITEM:[],SLIP:""}}formValue(){const t=a(this.el),e=class{static toTree(t,e=null){const s={};for(const i in t){if(!t.hasOwnProperty(i))continue;const n=this.keyRelative(i,e);if(null==n)continue;const o=this.valueKeyChain(n);this.setValueByKeyChain(s,o,t[i])}return s}static keyRelative(t,e){return null==e?t:0!==t.indexOf(e)?null:t.replace(e,"")}static valueKeyChain(t){const e=t.split("["),s=[];for(const t of e){if(""===t&&0===s.length)continue;const e=t.replace(/]$/,"");s.push(e)}return s}static setValueByKeyChain(t,e,s){const i=e.pop();let n=t;for(let t=0;t<e.length;++t){const s=e[t],o=t+1===e.length?i:e[t+1];null==n[s]&&(n[s]=/^\d+$/.test(o)?[]:{}),n=n[s]}n[i]=s}}.toTree(t,this.options.name);return Object.assign({ITEM:[],SLIP:""},e)}getMessage(t,e=null){const s="ITEM_DIGITAL_"+t,i=this.options.messages[s];return null!=i?d(i,e):super.getMessage(t,e)}}m.defaults=Object.assign({},h.defaults,{total:0});class p{constructor(t){this.options=Object.assign({},this.constructor.defaults,t),this._wires=[]}destroy(){this.forgetWires(),this.options={}}getMessage(t){return this.options.messages[t]||t}getTitle(){return this.options.title}extendOptions(t){this.options=Object.assign(this.options,{title:t.NAME})}render(t,e){const s=Object.keys(e.COLUMNS);s.unshift("INDEX"),this.forgetWires(),this.extendOptions(t),this.el=i(`<tr>\n\t\t\t${s.map((e=>this.renderColumn(t,e))).join("")}\n\t\t\t${this.renderActions()}\n\t\t</tr>`,"tbody"),this.setupWires()}mount(t){t.appendChild(this.el)}renderColumn(t,e){const s="column"+e.split("_").map((t=>t.substr(0,1).toUpperCase()+t.substr(1).toLowerCase())).join("");return s in this?this[s](t,e):this.columnDefault(t,e)}columnIndex(t,e){return`<td class="for--${n(e)}">\n\t\t\t<input type="hidden" name="${this.getName("ID")}" value="${this.value(t,"ID")}" />\n\t\t\t${this.valueFormatted(t,e)}\n\t\t</td>`}columnCis(t,e){const s=new c({messages:this.options.messages,name:this.getName("CIS"),required:!!this.value(t,"MARKING_GROUP"),total:this.value(t,"COUNT"),instances:this.value(t,"INSTANCES"),internalInstances:this.value(t,"INTERNAL_INSTANCES"),onChange:this.options.onChange});return this.wire(e,s),`<td class="for--${n(e)}" data-wire="${e}">${s.build()}</td>`}columnDigital(t,e){const s=new m({messages:this.options.messages,name:this.getName("DIGITAL"),total:this.value(t,"COUNT"),onChange:this.options.onChange});return this.wire(e,s),`<td class="for--${n(e)}" data-wire="${e}">${s.build()}</td>`}columnSubsidy(t,e){const s=this.value(t,"PROMOS");let i=this.valueFormatted(t,e);return null!=s&&Array.isArray(s)&&(i+=s.map((t=>`<div>${t}</div>`)).join("")),`<td class="for--${n(e)}">${i}</td>`}columnCount(t,e){const s=this.value(t,e),i=parseFloat(s)||"";return`<td class="for--${n(e)}">\n\t\t\t<input type="hidden" name="${this.getName("INITIAL_COUNT")}" value="${i}" />\n\t\t\t<input type="hidden" name="${this.getName("COUNT")}" value="${i}" />\n\t\t\t${this.valueFormatted(t,e)} ${this.getMessage("ITEM_UNIT")}\n\t\t</td>`}columnDefault(t,e){return`<td class="for--${n(e)}">${this.valueFormatted(t,e)}</td>`}renderActions(){return""}wire(t,e){this._wires[t]=e}forgetWires(){this._wires={}}setupWires(){for(const[t,e]of Object.entries(this._wires)){const s=this.el.querySelector(`[data-wire="${t}"]`).firstElementChild;s&&e.setup(s)}}callWires(t,e=[]){for(const[,s]of Object.entries(this._wires)){if("function"!=typeof s[t])return;s[t].apply(s,e)}}valueFormatted(t,e){const s=e+"_FORMATTED";let i="";return null!=t[s]?i=t[s]:null!=t[e]&&(i=t[e]),""!==i?i:"&mdash;"}value(t,e){return t[e]}getName(t){return this.options.name+"["+t+"]"}hasAction(t){return-1!==this.options.actions.indexOf(t)}}p.defaults={messages:{},name:null,title:null,actions:[],onChange:null};class g extends p{constructor(...t){super(...t),this.onCountChange=t=>{const e=parseInt(t.target.value);isNaN(e)||this.callWires("updateTotal",[e])},this.onDeleteToggle=t=>{const e=!!t.target.checked;this.el.classList.toggle("is--delete",e),this.disableInput(e)}}destroy(){this.unbind(),super.destroy()}unbind(){this.handleInputChange(!1),this.handleDeleteToggle(!1)}handleInputChange(t){this.options.onChange&&this.el.querySelectorAll("input").forEach((e=>{e.name===this.getName("COUNT")&&e[t?"addEventListener":"removeEventListener"]("change",this.onCountChange),e[t?"addEventListener":"removeEventListener"]("change",this.options.onChange)}))}handleDeleteToggle(t){const e=this.el.querySelector(".yamarket-delete-toggle__input");this.hasAction(g.ACTION_ITEM)&&e&&e[t?"addEventListener":"removeEventListener"]("change",this.onDeleteToggle)}disableInput(t){this.el.querySelectorAll("input").forEach((e=>{e.name!==this.getName("DELETE")&&(e.readonly=t,e.classList.contains("ui-ctl-element")&&e.parentElement.classList.toggle("ui-ctl-disabled",t))}))}getCountDiff(){const t=this.getInputValue("DELETE"),e=parseInt(this.getInputValue("INITIAL_COUNT")),s=parseInt(this.getInputValue("COUNT"));let i;return i=t?e:e-s,i}render(t,e){super.render(t,e),this.handleInputChange(!0),this.handleDeleteToggle(!0)}columnCount(t,e){if(!this.hasAction(g.ACTION_ITEM))return super.columnCount(t,e);const s=this.value(t,e),i=parseFloat(s)||"";return`<td class="for--${n(e)}">\n\t\t\t<input type="hidden" name="${this.getName("INITIAL_COUNT")}" value="${i}" />\n\t\t\t<div class="ui-ctl ui-ctl-sm ui-ctl-textbox ui-ctl-w100">\n\t\t\t\t<input\n\t\t\t\t\tclass="ui-ctl-element"\n\t\t\t\t\ttype="number"\n\t\t\t\t\tname="${this.getName("COUNT")}"\n\t\t\t\t\tvalue="${i}"\n\t\t\t\t\tmin="1"\n\t\t\t\t\tmax="${i}"\n\t\t\t\t\tstep="1"\n\t\t\t\t/>\n\t\t\t</div>\n\t\t</td>`}renderActions(){return this.hasAction(g.ACTION_ITEM)?`<td class="for--delete">\n\t\t\t<label class="yamarket-delete-toggle">\n\t\t\t\t<input class="yamarket-delete-toggle__input" type="checkbox" name="${this.getName("DELETE")}" value="Y" />\n\t\t\t\t<span class="yamarket-delete-toggle__icon icon--delete" title="${this.getMessage("ITEM_DELETE")}">${this.getMessage("ITEM_DELETE")}</span>\n\t\t\t\t<span class="yamarket-delete-toggle__icon icon--restore" title="${this.getMessage("ITEM_RESTORE")}">${this.getMessage("ITEM_RESTORE")}</span>\n\t\t\t</label>\n\t\t</td>`:""}getInputValue(t){const e=this.getInput(t);let s;return null==e?null:(s="checkbox"===e.type?e.checked?e.value:null:e.value,s)}getInput(t){const e=this.getName(t);return this.el.querySelector(`input[name="${e}"]`)}}g.ACTION_ITEM="item",g.ACTION_CIS="cis",g.ACTION_DIGITAL="digital",g.defaults=Object.assign({},p.defaults);class v extends u{static create(t,e){const s=new v;return s.initialize(t,e),s}constructor(){super(),this.items=[]}countChanges(){const t=[];for(const e of this.items){if(!(e instanceof g))continue;const s=e.getCountDiff();s>0&&t.push({name:e.getTitle(),diff:s})}return t}render(t){const e=t.VALUE,s=this.renderTable(e),i=s.querySelector("tbody");this.extendOptions(t),this.renewItems(e.ITEMS).forEach(((t,s)=>{t.render(e.ITEMS[s],e),t.mount(i)})),this._wrapper.appendChild(s)}extendOptions(t){this.options=Object.assign(this.options,{actions:t.ACTIONS})}renewItems(t){return this.destroyItems(),this.createItems(t)}destroyItems(){for(const t of this.items)t.destroy();this.items=[]}createItems(t){let e=0;this.items=[];for(const s of t)this.items.push(this.createItem(e)),++e;return this.items}createItem(t){const e={messages:v.messages,name:`${this.options.name}[${t}]`,actions:this.options.actions,onChange:this.hasAction(g.ACTION_CIS)||this.hasAction(g.ACTION_DIGITAL)?()=>{this._mode!==BX.UI.EntityEditorMode.edit&&(this._mode=BX.UI.EntityEditorMode.edit,this._editor.showToolPanel(),this._editor.registerActiveControl(this)),this._changeHandler()}:this._changeHandler};return this._mode===BX.UI.EntityEditorMode.edit?new g(e):new p(e)}renderTable(t){return i(`<div class="yamarket-basket">\n\t\t\t<div class="yamarket-basket-table-viewport">\n\t\t\t\t<table class="yamarket-basket-table">\n\t\t\t\t\t${this.renderHeader(t)}\n\t\t\t\t\t<tbody></tbody>\n\t\t\t\t</table>\n\t\t\t</div>\n\t\t\t${this.renderSummary(t)}\n\t\t</div>`)}renderHeader(t){return`<thead>\n\t\t\t<tr>\n\t\t\t\t<td class="for--index">&numero;</td>\n\t\t\t\t${Object.keys(t.COLUMNS).map((e=>`<td class="for--${n(e)}">${this.columnTitle(t,e)}</td>`)).join("")}\n\t\t\t\t${this.isInEditMode()&&this.hasAction(g.ACTION_ITEM)?'<td class="for--delete">&nbsp;</td>':""}\n\t\t\t</tr>\n\t\t</thead>`}columnTitle(t,e){const s="HEADER_"+e,i=this.getMessage(s);return i!==s?i:t.COLUMNS[e]}renderSummary(t){return 0===t.SUMMARY.length?"":`<div class="yamarket-basket-summary">\n\t\t\t${t.SUMMARY.map((t=>`<div class="yamarket-basket-summary__row">\n\t\t\t\t\t\t<div class="yamarket-basket-summary__label">${t.NAME}:</div>\n\t\t\t\t\t\t<div class="yamarket-basket-summary__value">${t.VALUE}</div>\n\t\t\t\t\t</div>`)).join("")}\n\t\t</div>`}hasAction(t){return-1!==this.options.actions.indexOf(t)}}v.messages={},v.defaults={name:"BASKET",actions:[]};class E extends u{static create(t,e){const s=new E;return s.initialize(t,e),s}validate(t){const e=this._parent.getChildById("BASKET");if(null==e)return;const s=e.countChanges();return 0!==s.length?this.showDialog(s,t):void 0}showDialog(t,e){return new Promise((s=>{const i=BX.UI.Dialogs.MessageBox.create({title:this.getMessage("MODAL_TITLE"),message:this.dialogBody(t)});i.setButtons([new BX.UI.SendButton({events:{click:this.onSendClick.bind(this,i,s)}}),new BX.UI.CancelButton({events:{click:this.onCancelClick.bind(this,i,e,s)}})]),i.show()}))}dialogBody(t){return`\n\t\t\t<div class="ui-form-row">\n\t\t\t\t<div class="ui-form-label">\n\t\t\t\t\t<div class="ui-ctl-label-text">${this.getMessage("REASON")}</div>\n\t\t\t\t</div>\n\t\t\t\t<div class="ui-form-content">\n\t\t\t\t\t${this.reasonControl()}\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t<div class="ui-form-row">\n\t\t\t\t<div class="ui-form-label">\n\t\t\t\t\t<div class="ui-ctl-label-text">${this.getMessage("PRODUCTS")}</div>\n\t\t\t\t</div>\n\t\t\t\t<div class="ui-form-content">\n\t\t\t\t\t${this.productsControl(t)}\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t<div class="ui-alert ui-alert-warning">${this.getMessage("FORM_INTRO")}</div>\n\t\t`}reasonControl(){return`<div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown ui-ctl-w100">\n\t\t\t<div class="ui-ctl-after ui-ctl-icon-angle"></div>\n\t\t\t<select class="ui-ctl-element" name="${this.options.name}[REASON]">\n\t\t\t\t${this.options.reasons.map((t=>`<option value="${t.ID}">${t.VALUE}</option>`)).join("")}\n\t\t\t</select>\n\t\t</div>`}productsControl(t){return t.map((t=>this.getMessage("ITEM_CHANGE",{NAME:t.name,COUNT:t.diff}))).join("<hr />")}onSendClick(t,e){o(t.popupWindow.contentContainer,this.el),t.close(),e()}onCancelClick(t,e,s){const i=BX.UI.EntityValidationError.create({field:this});e.addError(i),t.close(),s()}render(t){this.extendOptions(t),this.el=i(`<div class="ui-helper-hidden">\n\t\t\t<input type="hidden" name="${this.options.name}[REASON]" value="" />\n\t\t</div>`),this._wrapper.appendChild(this.el)}extendOptions(t){this.options=Object.assign(this.options,{reasons:t.ITEMS_CHANGE_REASON})}}E.messages={},E.defaults={name:"BASKET_CONFIRM",reasons:[]};class f{constructor(t){this.options=Object.assign({},this.constructor.defaults,t)}getMessage(t){return this.options.messages[t]||t}toggleUseDimensions(t){this.options.useDimensions=t}render(t){this.el=i(`<div class="yamarket-box">\n\t\t\t${this.buildDefined(t)}\n\t\t\t${this.buildHeader(t)}\n\t\t\t${this.buildBody(t)}\n\t\t</div>`)}buildDefined(t){const e=["FULFILMENT_ID"];let s="";for(const i of e)s+=`<input type="hidden" name="${this.options.name}[${i}]" value="${BX.util.htmlspecialchars(t[i]||"")}" />`;return s}buildSizes(t){return this.options.useDimensions?`${Object.keys(this.options.boxDimensions).map((e=>{var s,i;const n=this.getName("DIMENSIONS")+`[${e}]`,o=(null==t||null==(s=t.DIMENSIONS)||null==(i=s[e])?void 0:i.VALUE)||"";return""===o?"":`<input type="hidden" name="${n}" value="${BX.util.htmlspecialchars(o)}" data-name="${e}" />`})).join("")}`:""}buildHeader(t){return`<div class="yamarket-box__header">\n\t\t\t<div class="yamarket-box__title">\n\t\t\t\t${this.getMessage("BOX")}\n\t\t\t\t&numero;${t.NUMBER}\n\t\t\t</div>\n\t\t\t${this.buildProperties(t)}\n\t\t\t${this.buildActions(t)}\n\t\t</div>`}buildProperties(t){const e=this.disabledProperties();return`<div class="yamarket-box__properties">\n\t\t\t${Object.entries(this.options.boxProperties).map((([s,i])=>{var n;const o=(null==t||null==(n=t.PROPERTIES)?void 0:n[s])||"";return""===o||-1!==e.indexOf(s)?"":`<div class="yamarket-box__property">\n\t\t\t\t\t${i.NAME}: ${o} ${i.UNIT_FORMATTED||""}\n\t\t\t\t</div>`})).join("")}\n\t\t</div>`}disabledProperties(){return[]}buildActions(t){return""}buildBody(t){return this.buildSizes(t)}mount(t,e){null!=e?e.after(this.el):t.appendChild(this.el)}updateNumber(t){const e=this.el.querySelector(".yamarket-box__title"),s=this.el.querySelector('input[name$="[FULFILMENT_ID]"]');e.innerHTML=`${this.getMessage("BOX")} &numero;${t}`,s.value=this.options.fulfilmentBase+t}getName(t){return this.options.name+"["+t+"]"}fireChange(){this.options.onChange&&this.options.onChange()}}f.defaults={name:null,messages:{},boxProperties:{},boxDimensions:{},useDimensions:!1,fulfilmentBase:null,onChange:null};class I extends f{constructor(...t){super(...t),this.onDeleteClick=t=>{this.remove(),t.preventDefault()}}destroy(){this.unbind()}unbind(){this.handleInputChange(!1),this.handleDeleteClick(!1)}handleInputChange(t){this.options.onChange&&this.el.querySelectorAll("input").forEach((e=>{"hidden"!==e.type&&e[t?"addEventListener":"removeEventListener"]("change",this.options.onChange)}))}handleDeleteClick(t){const e=this.el.querySelector(".yamarket-box__delete");e&&e[t?"addEventListener":"removeEventListener"]("click",this.onDeleteClick)}toggleUseDimensions(t){if(super.toggleUseDimensions(t),null==this.el)return;const e=this.el.querySelector(".yamarket-box__body");null!=e&&(t?e.classList.remove("is--disabled"):e.classList.add("is--disabled"))}remove(){this.fireRemove(),this.destroy(),this.el.remove()}fireRemove(){this.el.dispatchEvent(new CustomEvent("yamarketBoxDelete",{detail:{item:this},bubbles:!0}))}render(t){super.render(t),this.handleInputChange(!0),this.handleDeleteClick(!0)}disabledProperties(){return["SIZE","WEIGHT"]}buildActions(t){return`<div class="yamarket-box__actions">\n\t\t\t<span class="yamarket-box__delete ui-entity-editor-header-edit-lnk">${this.getMessage("BOX_DELETE")}</span>\n\t\t</div>`}buildBody(t){return`<div class="yamarket-box__body ${this.options.useDimensions?"":"is--disabled"}">\n\t\t\t${this.buildSizes(t)}\n\t\t</div>`}buildSizes(t){return`<div class="yamarket-box__sizes">\n\t\t\t${Object.entries(this.options.boxDimensions).map((([e,s])=>{var i,n;const o=this.getName("DIMENSIONS")+`[${e}]`,a=(null==t||null==(i=t.DIMENSIONS)||null==(n=i[e])?void 0:n.VALUE)||"";return`<div class="yamarket-box__size">\n\t\t\t\t\t<div class="ui-entity-editor-content-block ui-entity-editor-field-text">\n\t\t\t\t\t\t<div class="ui-entity-editor-block-title ui-entity-widget-content-block-title-edit">\n\t\t\t\t            <label class="ui-entity-editor-block-title-text">${s.NAME}${s.UNIT_FORMATTED?", "+s.UNIT_FORMATTED:""}</label>\n\t\t\t\t        </div>\n\t\t\t\t        <div class="ui-ctl ui-ctl-textbox ui-ctl-w100">\n\t\t\t\t            <input class="ui-ctl-element" type="text" name="${o}" value="${BX.util.htmlspecialchars(a)}" size="6" data-name="${e}" />\n\t\t\t\t        </div>\n\t\t\t       </div>\n\t\t\t\t</div>`})).join("")}\n\t\t</div>`}}class y{constructor(t){this.onItemRemove=t=>{const e=t.detail.item;this.releaseItem(e),this.renewNumber(),this.fireChange()},this.onAddClick=t=>{this.addItem(),this.fireChange(),t.preventDefault()},this.options=Object.assign({},this.constructor.defaults,t)}getMessage(t){return this.options.messages[t]||t}handleItemRemove(){this.el.addEventListener("yamarketBoxDelete",this.onItemRemove)}handleAddClick(t){t.addEventListener("click",this.onAddClick)}toggleUseDimensions(t){if(this.options.useDimensions=t,null!=this.items)for(const e of this.items)e.toggleUseDimensions(t)}render(t){this.renderSelf(),this.renderItems(t),this.renderAddButton()}renderSelf(){this.el=i('<div class="yamarket-boxes"></div>')}renderItems(t){const e=[];let s=0;for(const i of t){const t=this.createItem(s);t.render(i),t.mount(this.el),e.push(t),++s}this.items=e,this.nextIndex=s,this.handleItemRemove()}createItem(t){const e={name:this.options.name+`[${t}]`,messages:this.options.messages,boxProperties:this.options.boxProperties,boxDimensions:this.options.boxDimensions,useDimensions:this.options.useDimensions,fulfilmentBase:this.options.fulfilmentBase,onChange:this.options.onChange};return this.options.mode===BX.UI.EntityEditorMode.edit?new I(e):new f(e)}releaseItem(t){const e=this.items.indexOf(t);-1!==e&&this.items.splice(e,1)}renewNumber(){let t=1;for(const e of this.items)e.updateNumber(t),++t}addItem(){var t;const e=this.items.length+1,s=this.createItem(this.nextIndex),i=null==(t=this.items[this.items.length-1])?void 0:t.el;s.render({FULFILMENT_ID:this.options.fulfilmentBase+e,NUMBER:e}),s.mount(this.el,i),this.items.push(s),++this.nextIndex}renderAddButton(){if(this.options.mode!==BX.UI.EntityEditorMode.edit)return"";const t=i(`<span class="yamarket-boxes__add ui-entity-editor-content-create-lnk">${this.getMessage("BOX_ADD")}</span>`);this.el.appendChild(t),this.handleAddClick(t)}fireChange(){this.options.onChange&&this.options.onChange()}mount(t){t.appendChild(this.el)}}y.defaults={name:null,messages:{},boxProperties:{},boxDimensions:{},useDimensions:!1,fulfilmentBase:null,onChange:null};class b extends u{constructor(...t){super(...t),this.onTitleActions=(t,e)=>{if(t!==this.getParent())return;const s=t.isInEditMode()?this.renderUseDimensions(this.useDimensions):this.definedUseDimensions(this.useDimensions);e.customNodes.push(s)},this.onUseDimensionsChange=t=>{const e=t.target.checked;this.toggleUseDimensions(e),this.markAsChanged()}}static create(t,e){const s=new b;return s.initialize(t,e),s}initialize(t,e){super.initialize(t,e),this.handleTitleActions(!0)}release(){this.handleTitleActions(!1),super.release()}handleTitleActions(t){BX[t?"addCustomEvent":"removeCustomEvent"](window,"BX.UI.EntityEditorSection:onLayout",this.onTitleActions)}handleUseDimensionsChange(t,e){t[e?"addEventListener":"removeEventListener"]("change",this.onUseDimensionsChange)}render(t){this.syncUseDimensions(t.USE_DIMENSIONS),this.renderShipments(t.VALUE,{fulfilmentBase:t.FULFILMENT_BASE,boxProperties:t.BOX_PROPERTIES,boxDimensions:t.BOX_DIMENSIONS,useDimensions:t.USE_DIMENSIONS})}definedUseDimensions(t=!1){return i(`<input type="hidden" name="USE_DIMENSIONS" value="${t?"Y":"N"}" />`)}renderUseDimensions(t=!1){const e=i(`<div class="ui-entity-editor-field-checkbox">\n\t\t\t<input type="hidden" name="USE_DIMENSIONS" value="N" />\n\t\t\t<label class="ui-ctl ui-ctl-xs ui-ctl-wa ui-ctl-checkbox yamarket-boxes-use-dimensions">\n\t\t\t\t<input class="ui-ctl-element" type="checkbox" name="USE_DIMENSIONS" value="Y" ${t?"checked":""} />\n\t\t\t\t<div class="ui-ctl-label-text">${this.getMessage("USE_DIMENSIONS")}</div>\n\t\t\t</label>\n\t\t</div>`),s=e.querySelector('input[type="checkbox"]');return this.handleUseDimensionsChange(s,!0),e}syncUseDimensions(t){this.useDimensions=t}toggleUseDimensions(t){if(null!=this.shipmentBoxes)for(const e of this.shipmentBoxes)e.toggleUseDimensions(t)}renderShipmentTitle(t){const e=`<div class="ui-entity-editor-block-title">\n\t\t\t<span class="ui-entity-editor-block-title-text">${this.getMessage("SHIPMENT",{ID:t.ID})}</span>\n\t\t</div>`;this._wrapper.insertAdjacentHTML("beforeend",e)}renderShipments(t,e){this.shipmentBoxes=[],Array.isArray(t)||(t=[]);const s=t.length>1;let i=0;for(const n of t)s&&this.renderShipmentTitle(n),this.renderShipment(n,Object.assign(e,{name:`${this.options.name}[${i}]`})),++i}renderShipment(t,e){this.renderShipmentDefined(t,e),this.renderBoxCollection(t.BOX,e)}renderShipmentDefined(t,e){const s=["ID"];for(const i of s)null!=t[i]&&this._wrapper.insertAdjacentHTML("afterbegin",`<input type="hidden" name="${e.name}[${i}]" value="${t[i]}" />`)}renderBoxCollection(t,e){const s=new y(Object.assign(e,{messages:b.messages,mode:this._mode,name:e.name+"[BOX]",onChange:this._changeHandler}));s.render(t),s.mount(this._wrapper),this.shipmentBoxes.push(s)}}b.messages={},b.defaults={name:"SHIPMENT",actions:[]};class T extends u{constructor(...t){super(...t),this.onActivityEnd=t=>{this.isMatchActivity(t)&&this._editor.reload()}}static create(t,e){const s=new T;return s.initialize(t,e),s}useTitle(){return!0}bind(){this.handleActivityEnd(!0)}unbind(){this.handleActivityEnd(!1)}handleActivityEnd(t){if(null==this._activityType)return;const e=this.getElement("broadcast");BX[t?"addCustomEvent":"removeCustomEvent"](e,"yamarketActivitySubmitEnd",this.onActivityEnd)}isMatchActivity(t){return null!=t&&(t===this._activityType||0===t.indexOf(this._activityType+"|"))}forget(){this.el&&this.unbind()}render(t){var e;const s=this.build(t);this._activityType=null==t||null==(e=t.ACTIVITY_ACTION)?void 0:e.TYPE,this.el=i(s),this.bind(),this._wrapper.appendChild(this.el)}build(t){const e=t.ACTIVITY_ACTION;return`<div class="ui-entity-editor-content-block">\n\t\t\t<div class="ui-entity-editor-content-block-text">\n\t\t\t\t${BX.util.htmlspecialchars(t.VALUE)}\n\t\t\t\t${e?this.buildActivity(e,t):""}\n\t\t\t</div>\n\t\t</div>`}buildActivity(t,e){const s=t.TEXT!==e.NAME?t.TEXT:this.getMessage("ACTIVITY_APPLY");return`<small>\n\t\t\t<a href="#" onclick='${this.makeActivityMethod(t)}; return false'>${s}</a>\n\t\t</small>`}makeActivityMethod(t){let e;if(null!=t.MENU){const s=t.MENU.map((t=>({text:t.TEXT,onclick:t.METHOD})));e=`BX.PopupMenu.show("${t.TYPE}", this, ${JSON.stringify(s)}, { angle: { offset: 50 } })`}else e=t.METHOD;return e}}T.messages={},T.defaults={broadcastElement:"#YAMARKET_ORDER_VIEW"};class C extends u{constructor(...t){super(...t),this.onDocumentClick=t=>{const e=t.currentTarget.dataset.type,s=this.getItem(e),i=this.buildDocumentUrl(e);this.createDialog(i,s).Show(),t.preventDefault()}}static create(t,e){const s=new C;return s.initialize(t,e),s}bindDocumentsClick(){this._wrapper.querySelectorAll(".yamarket-print__link").forEach((t=>{t.addEventListener("click",this.onDocumentClick)}))}getItem(t){let e;for(const s of this.options.items)if(s.TYPE===t){e=s;break}return e}buildDocumentUrl(t){const e=this.options.url;return e+(-1===e.indexOf("?")?"?":"&")+"type="+t}createDialog(t,e){return new BX.YandexMarket.PrintDialog({title:e.DIALOG_TITLE||e.TITLE,content_url:t,width:e.WIDTH||this.options.width,height:e.HEIGHT||this.options.height,buttons:[BX.YandexMarket.PrintDialog.btnSave,BX.YandexMarket.PrintDialog.btnCancel]})}render(t){this.extendOptions(t),this.renderIntro(),this.renderDocuments(t.ITEMS),this.bindDocumentsClick()}extendOptions(t){this.options=Object.assign(this.options,{url:t.URL,items:t.ITEMS})}renderIntro(){const t=i(`<p class="yamarket-print__intro">${this.getMessage("INTRO")}</p>`);this._wrapper.appendChild(t)}renderDocuments(t){if(!Array.isArray(t))return;const e=i(`<ul class="yamarket-print__documents">\n\t\t\t${t.map((t=>`<li class="yamarket-print__document">\n\t\t\t\t<a class="yamarket-print__link" href="#" data-type="${t.TYPE}">${t.TITLE}</a>\n\t\t\t</li>`)).join("")}\n\t\t</ul>`);this._wrapper.appendChild(e)}}C.messages={},C.defaults={url:null,width:400,height:300,items:[]};class _ extends u{static create(t,e){const s=new _;return s.initialize(t,e),s}render(t){if(!Array.isArray(t))return;const e=t.map((t=>`<div class="ui-alert ui-alert-${t.type}">${t.text}</div>`)).join("");this._wrapper.insertAdjacentHTML("beforeend",e)}}_.defaults={};new s({map:{notification:_,basket:v,basket_confirm:E,shipment:b,property:T,print:C}}).register();(new class{constructor(){this.onEditorInit=(t,e)=>{"yamarket_order_tab"===e.id&&(this.overrideEditor(t),this.overrideEditorAjax(t),this.restoreOriginDefault())}}start(){this.storeOriginDefault(),this.handleEditorInit()}handleEditorInit(){BX.addCustomEvent(window,"BX.UI.EntityEditor:onInit",this.onEditorInit)}storeOriginDefault(){var t,e,s;this._originDefault=null==(t=BX)||null==(e=t.UI)||null==(s=e.EntityEditor)?void 0:s.getDefault()}restoreOriginDefault(){null!=this._originDefault&&setTimeout((()=>{var t,e,s;null==(t=BX)||null==(e=t.UI)||null==(s=e.EntityEditor)||s.setDefault(this._originDefault)}))}overrideEditor(t){const e=t.createAjaxForm;Object.assign(t,{validate:this.editorValidate.bind(this,t),createAjaxForm:this.editorCreateAjaxForm.bind(this,t,e)})}editorValidate(t,e){const s=BX.UI.EntityAsyncValidator.create();for(const i of t._activeControls)s.addResult(i.validate(e));return this._userFieldManager&&s.addResult(this._userFieldManager.validate(e)),Promise.resolve(s.validate())}editorCreateAjaxForm(t,e,s,i){const n=e.call(t,s,i);return this.overrideAjaxForm(n),n}overrideEditorAjax(t){this.overrideAjaxForm(t._ajaxForm)}overrideAjaxForm(t){var e,s;t&&null!=(e=BX)&&null!=(s=e.UI)&&s.ComponentAjax&&t instanceof BX.UI.ComponentAjax&&Object.assign(t,{doSubmit:this.ajaxFormDoSubmit})}ajaxFormDoSubmit(t){const e=this._elementNode?BX.ajax.prepareForm(this._elementNode):{data:BX.clone(this._formData),filesCount:0};if(BX.type.isPlainObject(t.data))for(const s in t.data)t.data.hasOwnProperty(s)&&(e.data[s]=t.data[s]);const s=e.filesCount>0?this.makeFormData(e):e;BX.ajax.runComponentAction(this._className,this._actionName,{mode:"ajax",signedParameters:this._signedParameters,data:s,getParameters:this._getParameters}).then((t=>{const e=BX.prop.getFunction(this._callbacks,"onSuccess",null);e&&(BX.onCustomEvent(window,"BX.UI.EntityEditorAjax:onSubmit",[t.data.ENTITY_DATA,t]),e(t.data))})).catch((t=>{const e=BX.prop.getFunction(this._callbacks,"onFailure",null);if(e){for(var s=[],i=t.errors,n=0,o=i.length;n<o;n++)s.push(i[n].message);BX.onCustomEvent(window,"BX.UI.EntityEditorAjax:onSubmitFailure",[t.errors]),e({ERRORS:s})}}))}}).start(),t.Notification=_,t.Basket=v,t.BasketConfirm=E,t.Shipment=b,t.Property=T,t.Print=C}(this.BX.YandexMarket.UI.EntityEditor=this.BX.YandexMarket.UI.EntityEditor||{});
//# sourceMappingURL=script.js.map
