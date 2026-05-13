from PIL import Image
import sys

path = sys.argv[1]
img = Image.open(path)
print(img.format, img.size, img.mode)
pixels = img.convert('RGB').getdata()
from collections import Counter
counts = Counter(pixels)
print('most common', counts.most_common(5))
