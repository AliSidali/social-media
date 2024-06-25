export function isImage(attachment){
    let mime = attachment.mime || attachment.type;
    mime = mime.split('/');
    return mime[0].toLowerCase() ==='image' && mime[1].toLowerCase() !== 'vnd.adobe.photoshop';

}
