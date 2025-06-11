export function helpers(){
  function  isImage(attachment){
        let mime = attachment.mime || attachment.type;
        mime = mime.split('/');
        return mime[0].toLowerCase() ==='image' && mime[1].toLowerCase() !== 'vnd.adobe.photoshop';

    }

    function readFile(file){
        return new Promise((resolve, reject)=>{
            if(isImage(file)){

                const reader = new FileReader();
                reader.onload = ()=>{
                    resolve(reader.result);
                }
                reader.readAsDataURL(file);
            }else{
                resolve(null);
            }

        })


    }

    function hasUrlQueryParam(url, param){
            const urlObject = new URL(url); //URL Object
            const queryParam = urlObject.search; //like ?page=2
            const paramObject = new URLSearchParams(queryParam) // it returns ex:  ?page=2 to an object { page → "2" }
            return paramObject.has(param)
    }

    return {
        isImage,
        readFile,
        hasUrlQueryParam
    }
}

