{"version":3,"sources":["user_consent.js"],"names":["UserConsentControl","params","this","caller","formNode","controlNode","inputNode","config","prototype","BX","UserConsent","msg","title","btnAccept","btnReject","loading","errTextLoad","events","save","refused","accepted","current","autoSave","isFormSubmitted","isConsentSaved","attributeControl","load","context","item","find","bind","loadAll","limit","forEach","loadFromForms","formNodes","document","getElementsByTagName","convert","nodeListToArray","controlNodes","querySelectorAll","map","createItem","filter","submitEventName","addCustomEvent","onSubmit","onClick","querySelector","JSON","parse","getAttribute","parameters","tagName","findParent","e","url","requestForItem","preventDefault","check","checked","saveConsent","setCurrent","requestConsent","id","sec","replace","onAccepted","onRefused","actionRequestUrl","actionUrl","onCustomEvent","submit","initPopup","popup","isInit","nodes","container","shadow","head","loader","content","textarea","buttonAccept","buttonReject","onAccept","hide","onReject","init","tmplNode","createElement","innerHTML","children","body","insertBefore","link","linkA","textContent","message","setTitle","text","setContent","style","display","setUrl","href","show","isContentVisible","cache","list","stringifyKey","key","type","isString","stringify","set","data","get","push","getData","filtered","length","has","sendData","cacheHash","setTextToPopup","sendActionRequest","alert","titleBar","textTitlePos","indexOf","textTitleDotPos","substr","trim","split","Function","call","String","callback","window","location","originId","inputs","input","name","value","originatorId","apply","action","callbackSuccess","callbackFailure","sessid","bitrix_sessid","ajax","method","timeout","dataType","processData","onsuccess","proxy","error","onfailure","ready"],"mappings":"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","file":"user_consent.map.js"}