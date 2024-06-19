export function isImage(attachment){
    let mime = attachment.mime || attachment.type;
    console.log(attachment);

    mime = mime.split('/');
    console.log(mime[1]);
    return mime[0].toLowerCase() ==='image' && mime[1].toLowerCase() !== 'vnd.adobe.photoshop';

}
