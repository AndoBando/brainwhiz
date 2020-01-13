# authors : Guillaume Lemaitre <g.lemaitre58@gmail.com>
# license : MIT

from __future__ import print_function

import pydicom
import sys
import numpy as np
from PIL.Image import fromarray
import os


def myprint(dataset, indent=0, indent_string=None):
    """Go through all items in the dataset and print them with custom format

    Modelled after Dataset._pretty_str()
    """
    dont_print = ['Pixel Data', 'File Meta Information Version']

    if(indent_string==None):
        indent_string = "   " * indent

    for data_element in dataset:
        if data_element.VR == "SQ":   # a sequence
            print(indent_string, data_element.name)
            for sequence_item in data_element.value:
                myprint(sequence_item, indent,data_element.name+".")
        else:
            if data_element.name in dont_print:
#               print("""<item not printed -- in the "don't print" list>""")
                pass
            else:
                repr_value = repr(data_element.value)
                if len(repr_value) > 50:
                    repr_value = repr_value[:50] + "..."
                print("{0:s} {1:s}:{2:s};".format(indent_string,
                                                   data_element.name,
                                                   repr_value))


def dicom2png():
    try:
        ds = pydicom.dcmread(sys.argv[1])
        shape = ds.pixel_array.shape
        # Convert to float to avoid overflow or underflow losses.
        image_2d = ds.pixel_array.astype(float)

        # Rescaling grey scale between 0-255
        image_2d_scaled = (np.maximum(image_2d,0) / image_2d.max()) * 255.0

        # Convert to uint
        image_2d_scaled = np.uint8(image_2d_scaled)

        # Write the PNG file
        with open("out"+'.png' , 'wb') as png_file:
            w = png.Writer(shape[1], shape[0], greyscale=True)
            w.write(png_file, image_2d_scaled)
    except:
        print('Could not convert: ')

ds = pydicom.dcmread(sys.argv[1])
dicom2png()
im = fromarray(ds.pixel_array)
im.save("test.jpg")
print('The image has {} x {} voxels'.format(im.shape[0],
                                            im.shape[1]))
myprint(ds)