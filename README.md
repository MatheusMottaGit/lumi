# Lumi Project

This project provides an API for handling file uploads, image retrieval, caption generation, and Instagram carousel posting.

---

## API Documentation

#### 1. **Split Upload**
**Endpoint**: `/split_upload`  
**Method**: `POST`  
**Controller**: `AwsS3Controller@handleUploadS3`  

##### Description:
Splits files into multiple parts and upload them to AWS S3 Bucket.

##### Request:
- **Headers**:
  - `Content-Type: multipart/form-data`
- **Body**:
  - `carouselFiles[]` (array of files): The files to be uploaded.
  - `dirName` (string): The directory name where the files will be stored.
  - `numberOfParts` (integer, optional): The number of parts to split the files into.

##### Response:
- **Success**: 
  ```json
  {
    "message": "Files uploaded successfully.",
    "data": ["part1.jpg", "part2.jpg", "part3.jpg"]
  }
  ```
- **Error**:
  ```json
  {
    "error": "Failed to upload files."
  }
  ```

---

#### 2. **Show Bucket Parts**
**Endpoint**: `/bucket/parts`  
**Method**: `GET`  
**Controller**: `AwsS3Controller@showImages`  

##### Description:
Retrieves a list of images stored in the AWS S3 bucket.

##### Request:
- **Headers**: None
- **Query Parameters**:
  - `dirName` (string, optional): The directory name to filter images.

##### Response:
- **Success**:
  ```json
  {
    "message": "Images retrieved successfully.",
    "data": ["image1.jpg", "image2.jpg", "image3.jpg"]
  }
  ```
- **Error**:
  ```json
  {
    "error": "Failed to retrieve images."
  }
  ```

---

#### 3. **Generate Post Caption**
**Endpoint**: `/caption/completion`  
**Method**: `POST`  
**Controller**: `ChatCompletionController@generatePostCaption`  

##### Description:
Generates a caption for a post using AI.

##### Request:
- **Headers**:
  - `Content-Type: application/json`
- **Body**:
  - `prompt` (string): The input text to generate the caption.

##### Response:
- **Success**:
  ```json
  {
    "message": "Caption generated successfully.",
    "data": "This is the generated caption."
  }
  ```
- **Error**:
  ```json
  {
    "error": "Failed to generate caption."
  }
  ```

---

#### 4. **Post Instagram Carousel**
**Endpoint**: `/post/carousel`  
**Method**: `POST`  
**Controller**: `CarouselPostController@postInstagramCarousel`  

##### Description:
Posts a carousel of images to Instagram.

##### Request:
- **Headers**:
  - `Content-Type: application/json`
- **Body**:
  - `images` (array of strings): The list of image URLs to post.
  - `caption` (string): The caption for the post.

##### Response:
- **Success**:
  ```json
  {
    "message": "Carousel posted successfully.",
    "data": {
      "postId": "1234567890"
    }
  }
  ```
- **Error**:
  ```json
  {
    "error": "Failed to post carousel."
  }
  ```

---

### Notes
- Ensure that the AWS S3 credentials and configurations are properly set up in the environment variables.
- The AI caption generation feature requires an API key for the AI service (OpenAI).

---

### Environment Variables
- `AWS_ACCESS_KEY_ID`: AWS access key.
- `AWS_SECRET_ACCESS_KEY`: AWS secret key.
- `AWS_BUCKET_NAME`: Name of the S3 bucket.
- `NEXT_PUBLIC_API_URL`: Base URL for the API.
