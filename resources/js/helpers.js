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

    return {
        isImage,
        readFile
    }
}

