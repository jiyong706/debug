import cv2
import sys
import os
import easyocr

os.environ["KMP_DUPLICATE_LIB_OK"] = "TRUE"

def process_image(image_path, output_path, overlay_path, x, y, width, height):
    log = []
    log.append(f"Processing image: {image_path}")
    if not os.path.isfile(image_path):
        log.append(f"Error: {image_path} does not exist or is not a file.")
        return log

    image = cv2.imread(image_path)
    
    if image is None:
        log.append(f"Error: Unable to read image from {image_path}")
        return log
    else:
        log.append(f"Image successfully read from {image_path}")

    # Adjust selected region coordinates if necessary
    if x < 0: x = 0
    if y < 0: y = 0
    if x + width > image.shape[1]: width = image.shape[1] - x
    if y + height > image.shape[0]: height = image.shape[0] - y

    # Extract selected region
    selected_region = image[y:y+height, x:x+width]
    
    # 텍스트 추출 영역을 네모 박스로 표시
    overlay_image = image.copy()
    cv2.rectangle(overlay_image, (x, y), (x+width, y+height), (0, 255, 0), 2)  # Green rectangle with thickness 2

    # 이미지 쓰기
    if not cv2.imwrite(output_path, selected_region):
        log.append(f"Error: Unable to write image to {output_path}")
    else:
        log.append(f"Selected region image successfully written to {output_path}")

    if not cv2.imwrite(overlay_path, overlay_image):
        log.append(f"Error: Unable to write overlay image to {overlay_path}")
    else:
        log.append(f"Overlay image successfully written to {overlay_path}")

    # EasyOCR로 텍스트 추출
    log.append("Initializing EasyOCR Reader...")
    reader = easyocr.Reader(['ko', 'en'])
    log.append("Reading text from selected region...")
    result = reader.readtext(output_path)

    extracted_text = "\n".join([res[1] for res in result])
    log.append(f"Extracted text: {extracted_text}")

    # 파일이 저장될 디렉토리 확인 및 생성
    text_file_path = r"C:\xampp\htdocs\pilleat\extracted_text.txt"
    text_file_dir = os.path.dirname(text_file_path)
    if text_file_dir and not os.path.exists(text_file_dir):
        try:
            os.makedirs(text_file_dir)
            log.append(f"Directory {text_file_dir} created")
        except Exception as e:
            log.append(f"Error creating directory {text_file_dir}: {e}")
            return log

    # 현재 작업 디렉토리 출력
    log.append(f"Current working directory: {os.getcwd()}")

    # 추출된 텍스트를 파일에 저장
    try:
        with open(text_file_path, "w", encoding="utf-8") as f:
            f.write(extracted_text)
        log.append(f"Text successfully written to {text_file_path}")
    except Exception as e:
        log.append(f"Error writing to file: {e}")

    return log

if __name__ == "__main__":
    if len(sys.argv) != 8:
        print("Usage: python image_processing.py <input_image> <output_image> <overlay_image> <x> <y> <width> <height>")
    else:
        print("Arguments received:")
        print(sys.argv)
        logs = process_image(sys.argv[1], sys.argv[2], sys.argv[3], int(sys.argv[4]), int(sys.argv[5]), int(sys.argv[6]), int(sys.argv[7]))
        for log in logs:
            print(log)
