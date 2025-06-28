export function helpers(){
  function  isImage(attachment){
        let mime = attachment.mime || attachment.type;
        mime = mime.split('/');
        return mime[0].toLowerCase() ==='image' && mime[1].toLowerCase() !== 'vnd.adobe.photoshop';

    }

    function isVideo(attachment){
        let mime = attachment.mime || attachment.type
        mime = mime.split('/');
        return mime[1].toLowerCase() === 'mp4';
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
            const paramObject = getQueryParamObject(url)
            return paramObject.has(param)
    }

    function getQueryParamObject(url){
        const urlObject = new URL(url); //URL Object

             const queryParam = urlObject.search; //like ?page=2

             return new URLSearchParams(queryParam) // it returns ex:  ?page=2 to an object { page → "2" }
    }

    function isUrl(url){
        const pattern = new RegExp('^(https?:\\/\\/)(([a-zA-Z])([a-zA-Z\\d-])*([a-zA-Z\\d])\\.)+[a-z]{2,}');
        return pattern.test(url);
    }



    return {
        isImage,
        readFile,
        hasUrlQueryParam,
        isVideo,
        getQueryParamObject,
        isUrl
    }
}

